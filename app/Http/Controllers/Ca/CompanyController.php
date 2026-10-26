<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Concerns\AppliesProspectListIndexFilters;
use App\Http\Controllers\Concerns\BuildsEntrepriseQuestionnaireResults;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Agence;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Person;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Region;
use App\Models\Tier;
use App\Models\User;
use App\Services\ClientEntrepriseTableExportService;
use App\Services\EntrepriseDuplicateGuard;
use App\Services\Inertia\EntrepriseListPresenter;
use App\Services\Inertia\EntrepriseShowPage;
use App\Services\ProspectEntrepriseTableExportService;
use App\Services\TableDocumentExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    use AppliesEntrepriseListIndexFilters;
    use AppliesProspectListIndexFilters;
    use BuildsEntrepriseQuestionnaireResults;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Même périmètre (agence) et mêmes filtres que fetchPaginated / fetchStats.
        $query = $this->applyFilters($this->baseQuery()->clone(), $this->parseFilters($request), true);
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Entreprises',
            'subtitle' => 'Portefeuille clients de votre agence.',
            'countLabel' => 'entreprise(s)',
            'showRoute' => 'ca.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'taille', 'capital', 'gestionnaire', 'statut'],
            'filters' => ['region_id', 'taille', 'forme_id', 'caractere', 'client_structuration_status', 'gestionnaire_id', 'promu_client'],
            'gestionnaires' => $list->agenceGestionnaireOptions($this->baseQuery())['gestionnaires'],
            'exportRoute' => 'ca.entreprises.export',
            'stats' => $list->standardStats($this->fetchStats($request)->getData(true), 'Total entreprises'),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('ca.dashboard')],
                ['label' => 'Entreprises'],
            ],
        ]);
    }

    public function getProspects(Request $request)
    {
        // Même périmètre (prospects soumis) et mêmes filtres que fetchProspectsPaginated.
        $query = $this->applyProspectIndexFilters($this->prospectsQuery(), $this->parseProspectIndexFilters($request), ['apply_submission' => false]);
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Prospects',
            'subtitle' => 'Prospects soumis pour avis — visibilité nationale (brouillons exclus).',
            'countLabel' => 'prospect(s)',
            'showRoute' => 'ca.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'taille', 'capital', 'agence', 'statut'],
            'filters' => ['region_id', 'taille', 'forme_id', 'caractere', 'agence_id', 'gestionnaire_id', 'created'],
            'exportRoute' => 'ca.prospects.export',
            'stats' => $list->standardStats($this->fetchProspectsStats($request)->getData(true), 'Total prospects'),
            'empty' => 'Aucun prospect trouvé.',
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('ca.dashboard')],
                ['label' => 'Prospects'],
            ],
        ] + $list->agenceGestionnaireOptions($this->prospectsQuery()));
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    private function baseQuery()
    {
        return Entreprise::where('prospect', 0)->where('agence_id', auth()->user()->agence_id);
    }

    public function fetchStats(Request $request)
    {
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters, true);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
        ];

        return response()->json($stats);
    }

    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters, true);

        $recordsTotal = $this->baseQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->with('dossierEntreeRelation')->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Export Excel / PDF du portefeuille clients (mêmes filtres et recherche que le tableau).
     */
    public function exportClients(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $search = trim((string) $request->input('search.value', ''));
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters, true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['dossierEntreeRelation', 'agence', 'gestionnaire', 'region', 'arrondissement'])
            ->orderByDesc('created_at')
            ->get();

        $rows = ClientEntrepriseTableExportService::rowsCaGestionnaireAnalyste($items);

        return ClientEntrepriseTableExportService::download(
            $rows,
            ClientEntrepriseTableExportService::headersCaGestionnaireAnalyste(),
            $format,
            'ca-clients',
            'Chef d\'agence — portefeuille clients',
        );
    }

    private function parseFilters(Request $request): array
    {
        $promuGestionnaire = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        $promuGestionnaire['agence_id'] = null;

        return array_merge(
            [
                'region_id' => $request->input('region_id'),
                'taille' => $request->input('taille'),
                'forme_id' => $request->input('forme_id'),
                'caractere' => $request->input('caractere'),
                'client_structuration_status' => DossierEntreeRelation::normalizeClientStructurationFilter($request->input('client_structuration_status')),
            ],
            $promuGestionnaire,
        );
    }

    private function applyFilters($query, array $filters, bool $applyPromuAgenceGestionnaire = true)
    {
        if (! empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (! empty($filters['taille'])) {
            $query->where('taille', $filters['taille']);
        }
        if (! empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }
        if (! empty($filters['caractere'])) {
            $query->where('caractere', $filters['caractere']);
        }
        if (! empty($filters['client_structuration_status'])) {
            $query->whereClientStructurationStatus($filters['client_structuration_status']);
        }

        if ($applyPromuAgenceGestionnaire) {
            $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $filters, true);
        }

        return $query;
    }

    public function fetchFilterOptions()
    {
        $agenceId = (int) auth()->user()->agence_id;
        $gestionnaireIds = Entreprise::query()
            ->where('prospect', 0)
            ->where('agence_id', $agenceId)
            ->whereNotNull('gestionnaire_id')
            ->distinct()
            ->pluck('gestionnaire_id');

        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'gestionnaires' => User::query()
                ->whereIn('id', $gestionnaireIds)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function fetchProspects()
    {
        $items = $this->prospectsQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    /**
     * Listes déroulantes pour la page prospects (agence périmètre, gestionnaires ayant des prospects, etc.).
     */
    public function fetchProspectsFilterOptions()
    {
        $p = $this->prospectsQuery();
        $agenceIds = (clone $p)->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = (clone $p)->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');

        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'agences' => Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->get(['id', 'name']),
            'gestionnaires' => User::query()
                ->whereIn('id', $gestionnaireIds)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    private function prospectsQuery()
    {
        return Entreprise::query()->submittedProspect();
    }

    public function fetchProspectsStats(Request $request)
    {
        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, ['apply_submission' => false]);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
        ];

        return response()->json($stats);
    }

    public function fetchProspectsPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, ['apply_submission' => false]);

        $recordsTotal = $this->prospectsQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Export Excel / PDF des prospects soumis (mêmes filtres et recherche que le tableau).
     */
    public function exportProspects(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $search = trim((string) $request->input('search.value', ''));
        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, ['apply_submission' => false]);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['agence', 'gestionnaire', 'user', 'region', 'arrondissement'])
            ->orderByDesc('created_at')
            ->get();

        $rows = ProspectEntrepriseTableExportService::rowsCa($items);

        return TableDocumentExportService::downloadFormatted(
            $rows,
            ProspectEntrepriseTableExportService::headersCa(),
            $format,
            'ca-prospects',
            'Chef d\'agence — liste des prospects',
            'Prospects soumis pour avis — visibilité nationale (brouillons exclus)',
        );
    }

    public function saveProgramme(Request $request)
    {
        $data = $request->all();
        $data['token'] = sha1(time().rand(1, 100));
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        Dossier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'programme_id' => $request->programme_id,
            ],
            $data
        );
        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        $aid = (int) auth()->user()->agence_id;
        $item = Entreprise::query()
            ->where('token', $token)
            ->where(function ($q) use ($aid) {
                $q->where(function ($qClient) use ($aid) {
                    $qClient->where('prospect', 0)->where('agence_id', $aid);
                })->orWhere(function ($qProspect) {
                    $qProspect->submittedProspect();
                });
            })
            ->with([
                'forme',
                'filiere',
                'branche',
                'produit',
                'produits.filiere',
                'produits.branche',
                'appuis.type',
                'critereAvis.user',
                'sites.arrondissement',
                'sites.departement',
                'sites.region',
                'equipeMembres.site',
                'village',
                'quartier',
                'arrondissement',
                'departement',
                'region',
                'agence.representation',
                'tiers.person',
                'tiers.company.produit',
                'dossiers.programme',
                'dossiers.instructionProgrammes.programme',
                'dossiers.chefFiliereSubmittedToAgenceBy',
                'dossiers.instructionAgenceValidatedBy',
                'dossiers.instructionAgenceRejectedBy',
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'promuClientUser',
                'prospectRejectedUser',
                'dossierEntreeRelation.qualificationUser',
                'dossierEntreeRelation.programmesSubmittedBy',
                'dossierEntreeRelation.qualificationValidatedByAgenceUser',
                'dossierEntreeRelation.instructionValidatedBy',
                'dossierEntreeRelation.programmeSelections.programme',
                'dossierEntreeRelation.programmeSelections.instructionDossier',
                'dossierEntreeRelation.instructionBundleDossier.instructionProgrammes.programme',
                'dossierEntreeRelation.instructionBundleDossier.chefFiliereSubmittedToAgenceBy',
                'dossierEntreeRelation.instructionBundleSubmittedBy',
                'dossierEntreeRelation.instructionBundleValidatedBy',
                'dossierEntreeRelation.instructionBundleRejectedBy',
            ])
            ->firstOrFail();

        $page = app(EntrepriseShowPage::class);
        $sheet = $page->sheet($item, ['routePrefix' => 'ca']);

        // Décision du chef d'agence : écran de validation dès que les deux avis sont rendus.
        $actions = [];
        if ($item->prospect && $item->juridique_avis_at && $item->conformite_avis_at && ! $item->promu_client_at && ! $item->prospect_rejected_at) {
            $actions[] = ['label' => 'Validation / refus du prospect', 'icon' => 'gavel', 'variant' => 'success',
                'href' => route('ca.workflow.prospects.show', $item->token)];
        }

        // Réaffectation : fiches de l'agence du chef d'agence uniquement.
        $workflowActions = [];
        if ((int) $item->agence_id === $aid) {
            $currentId = (int) ($item->gestionnaire_id ?: $item->user_id);
            $gestionnaires = User::query()
                ->where('role_id', (int) (config('angara.role_gestionnaire') ?: 16))
                ->where('agence_id', $aid)
                ->where(fn ($q) => $q->where('active', true)->orWhereNull('active'))
                ->where('id', '!=', $currentId)
                ->orderBy('name')
                ->get(['id', 'name']);
            if ($gestionnaires->isNotEmpty()) {
                $actuel = $currentId ? User::find($currentId)?->name : null;
                $workflowActions[] = [
                    'key' => 'reassign',
                    'label' => 'Changer de gestionnaire',
                    'icon' => 'swap_horiz',
                    'variant' => 'secondary',
                    'title' => 'Attribuer la fiche à un autre gestionnaire',
                    'description' => ($actuel ? "Gestionnaire actuel : $actuel. " : '')
                        .'Le nouveau gestionnaire reprend la fiche et ses dossiers d\'instruction ; l\'ancien et le nouveau gestionnaire sont notifiés par e-mail.',
                    'fields' => [
                        ['name' => 'gestionnaire_id', 'label' => 'Nouveau gestionnaire', 'type' => 'select', 'required' => true,
                            'options' => $gestionnaires->map(fn ($u) => ['value' => $u->id, 'label' => $u->name])->values()],
                        ['name' => 'motif', 'label' => 'Motif (facultatif)', 'type' => 'textarea', 'required' => false],
                    ],
                    'submits' => [['label' => 'Attribuer', 'url' => route('ca.entreprises.gestionnaire.update', $item->token), 'variant' => 'primary']],
                ];
            }
        }

        return $page->render($item, 'ca', $sheet, [
            $page->link('ca.entreprise.get.engagements', $item->token, 'Grille des engagements', 'table_view'),
            $page->simulationLink($item),
        ], [
            'actions' => $actions,
            'workflowActions' => $workflowActions,
            'initialTab' => $item->prospect ? 'avis' : 'synthese',
        ]);
    }

    /**
     * Réaffectation d'un client / prospect de l'agence à un autre gestionnaire de la même agence.
     * Les dossiers d'instruction du client suivent ; l'ancien et le nouveau gestionnaire sont notifiés.
     */
    public function reassignGestionnaire(Request $request, string $token)
    {
        $aid = (int) auth()->user()->agence_id;
        $item = Entreprise::query()->where('token', $token)->where('agence_id', $aid)->firstOrFail();

        $roleGestionnaire = (int) (config('angara.role_gestionnaire') ?: 16);
        $data = $request->validate([
            'gestionnaire_id' => [
                'required', 'integer',
                \Illuminate\Validation\Rule::exists(User::class, 'id')
                    ->where('role_id', $roleGestionnaire)
                    ->where('agence_id', $aid),
            ],
            'motif' => ['nullable', 'string', 'max:2000'],
        ], [
            'gestionnaire_id.exists' => 'Le gestionnaire choisi doit appartenir à votre agence.',
        ]);

        $nouveau = User::findOrFail((int) $data['gestionnaire_id']);
        if ($nouveau->active === 0 || $nouveau->active === false) {
            return back()->withErrors(['gestionnaire_id' => 'Ce gestionnaire est désactivé.']);
        }
        $ancienId = $item->gestionnaire_id ?: $item->user_id;
        if ((int) $ancienId === (int) $nouveau->id) {
            return back()->with('info', 'Ce gestionnaire est déjà en charge de la fiche.');
        }
        $ancien = $ancienId ? User::find($ancienId) : null;

        DB::transaction(function () use ($item, $nouveau) {
            $item->gestionnaire_id = $nouveau->id;
            $item->save();
            Dossier::query()->where('entreprise_id', $item->id)->update(['gestionnaire_id' => $nouveau->id]);
        });

        \Illuminate\Support\Facades\Log::info('Réaffectation gestionnaire', [
            'entreprise_id' => $item->id,
            'ancien_gestionnaire_id' => $ancienId,
            'nouveau_gestionnaire_id' => $nouveau->id,
            'par_user_id' => auth()->id(),
            'motif' => $data['motif'] ?? null,
        ]);

        $notifier = app(\App\Services\WorkflowEmailNotificationService::class);
        $nature = $item->prospect ? 'le prospect' : 'le client';
        $motif = filled($data['motif'] ?? null) ? "\n\nMotif : ".$data['motif'] : '';
        $notifier->notifyUser($nouveau, auth()->user(), $notifier->buildPayload(
            subject: 'Nouveau portefeuille : '.$item->name,
            title: 'Une fiche vous a été attribuée',
            body: "Le chef d'agence vous a attribué $nature « {$item->name} ».$motif",
            ctaLabel: 'Ouvrir la fiche',
            ctaUrl: route('gestionnaire.entreprises.show', $item->token),
            event: 'entreprise.gestionnaire.reassigned',
        ), $notifier->contextForEntreprise($item));
        if ($ancien) {
            $notifier->notifyUser($ancien, auth()->user(), $notifier->buildPayload(
                subject: 'Fiche réaffectée : '.$item->name,
                title: 'Une fiche de votre portefeuille a été réaffectée',
                body: "Le chef d'agence a attribué $nature « {$item->name} » à {$nouveau->name}.$motif",
                event: 'entreprise.gestionnaire.reassigned',
            ), $notifier->contextForEntreprise($item));
        }

        return back()->with('success', 'Fiche attribuée à '.$nouveau->name.'.');
    }

    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu', $data['niu'])->where('phone', $data['phone'])->first();
        if (! $person) {
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function saveTiersMorale(Request $request)
    {
        // dd($request->all());
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = app(EntrepriseDuplicateGuard::class)->findForLinking($data, (int) $request->entreprise_id);
        if (! $entreprise) {
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function saveQuestionnaire(Request $request)
    {
        // dd($request->choices[1]);
        $choices = $request->choices;
        foreach ($choices as $choice) {
            QuestionAnswer::updateOrCreate([
                'entreprise_id' => $choice['entreprise_id'],
                'choice_id' => $choice['choice_id'],
            ], $choice);
        }

        // Session::flash('success','Enregistrement effectué avec succès!');
        return response()->json('ok');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
