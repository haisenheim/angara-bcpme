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
    public function index()
    {
        //
        return view('/Ca/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Ca/Companies/prospects');
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
        $agenceId = (int) auth()->user()->agence_id;
        $gestionnaireIds = $this->prospectsQuery()
            ->whereNotNull('gestionnaire_id')
            ->distinct()
            ->pluck('gestionnaire_id');

        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'agences' => Agence::query()->where('id', $agenceId)->orderBy('name')->get(['id', 'name']),
            'gestionnaires' => User::query()
                ->whereIn('id', $gestionnaireIds)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    private function prospectsQuery()
    {
        return Entreprise::query()
            ->where('prospect', 1)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id);
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
            'Prospects soumis pour avis — périmètre agence',
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $formes = Forme::all();

        return view('/Ca/Companies/create', compact('formes'));
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
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->where(function ($q) {
                $q->where('prospect', 0)
                    ->orWhere(function ($q2) {
                        $q2->where('prospect', 1)->whereNotNull('prospect_submitted_at');
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

        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();

        if ($item->prospect) {
            $item->load([
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'critereAvis.user',
                'arrondissement',
                'departement',
                'region',
                'forme',
                'agence.representation',
                'produit',
                'produits',
                'appuis.type',
                'reponses.question',
                'reponses.choice',
            ]);

            return view('Ca.Companies.show_prospect', compact('item', 'mr', 'checklist'));
        }

        return view('Ca.Companies.show', compact('item', 'mr', 'checklist'));
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
        $entreprise = Entreprise::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();
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

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();

        return view('/Ca/Companies/questionnaire', compact('item', 'criteres'));
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
