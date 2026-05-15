<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Requests\Analyste\UpdateDossierInstructionBudgetsRequest;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
use App\Models\DossierInstructionProgramme;
use App\Models\Instruction\IndicateurFinancier;
use App\Models\User;
use App\Services\DossierInstructionShowPresenter;
use App\Services\WorkflowEmailNotificationService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class DossierController extends Controller
{
    use StoresDossierPieces;

    //
    public function index()
    {
        //
        return view('/Analyste/Dossiers/index');
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->orderBy('created_at', 'DESC')->get();
        $items = DossierListResource::collection($items);

        return response()->json($items);
    }

    private function baseQuery()
    {
        $user = auth()->user();
        if ($user instanceof User && $user->isAnalysteFinancierNational()) {
            return Dossier::query();
        }

        return Dossier::where('analyste_id', auth()->user()->id);
    }

    private function authorizeAnalysteDossier(?Dossier $dossier): void
    {
        if (! $dossier) {
            abort(404);
        }
        $user = auth()->user();
        if ($user instanceof User && $user->isAnalysteFinancierNational()) {
            return;
        }
        if ((int) ($dossier->analyste_id ?? 0) !== (int) auth()->id()) {
            abort(403);
        }
    }

    /**
     * Mise à jour des totaux engagements (structuration) et des budgets appuis par programme.
     */
    public function updateInstructionBudgetsEngagements(UpdateDossierInstructionBudgetsRequest $request, Dossier $dossier)
    {
        $this->authorizeAnalysteDossier($dossier);

        if (! $dossier->analysteFinancierPeutMettreAJourBudgetsEtEngagements()) {
            return redirect()
                ->back()
                ->withErrors(['submission' => 'Les montants d’engagements et les budgets ne sont plus modifiables à ce stade du dossier.']);
        }

        $validated = $request->validated();

        foreach ($validated['programme_budgets'] ?? [] as $row) {
            $bf = isset($row['budget_appui_financier']) ? (float) $row['budget_appui_financier'] : 0.0;
            $bnf = isset($row['budget_appui_non_financier']) ? (float) $row['budget_appui_non_financier'] : 0.0;
            if ($bf <= 0 && $bnf <= 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors([
                        'programme_budgets' => 'Renseignez au moins un budget (appui financier ou non financier) pour chaque programme.',
                    ]);
            }
        }

        $connection = $dossier->getConnectionName();

        DB::connection($connection)->transaction(function () use ($dossier, $validated, $connection) {
            $fresh = Dossier::on($connection)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if (! $fresh->analysteFinancierPeutMettreAJourBudgetsEtEngagements()) {
                throw ValidationException::withMessages([
                    'submission' => 'Le dossier a évolué : les montants ne sont plus modifiables à ce stade.',
                ]);
            }
            $fresh->engagements_sollicites_total = $validated['engagements_sollicites_total'];
            $fresh->engagements_en_cours_total = $validated['engagements_en_cours_total'];
            $fresh->save();

            foreach ($validated['programme_budgets'] ?? [] as $row) {
                $dip = DossierInstructionProgramme::query()
                    ->whereKey($row['id'])
                    ->where('dossier_id', $fresh->id)
                    ->lockForUpdate()
                    ->firstOrFail();
                $dip->budget_appui_financier = isset($row['budget_appui_financier']) ? (float) $row['budget_appui_financier'] : 0.0;
                $dip->budget_appui_non_financier = isset($row['budget_appui_non_financier']) ? (float) $row['budget_appui_non_financier'] : 0.0;
                $dip->save();
            }
        });

        return redirect()->back()->with('success', 'Totaux engagements et budgets par programme ont été mis à jour.');
    }

    /**
     * Enregistrement du brouillon des rubriques d’analyse (Summernote), sans soumission au REXP.
     */
    public function saveInstructionAvisDraft(Request $request, Dossier $dossier)
    {
        $this->authorizeAnalysteDossier($dossier);

        if (! $dossier->analyste_id) {
            return redirect()
                ->back()
                ->withErrors(['submission' => 'Le dossier doit avoir un analyste affecté.']);
        }

        $user = auth()->user();
        $national = $user instanceof User && $user->isAnalysteFinancierNational();
        if (! $national && (int) $dossier->analyste_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($dossier->isInstructionSubmittedToExploitation()) {
            return redirect()->back()->with('info', 'Vous avez déjà soumis ce dossier au responsable exploitation : les rubriques d’analyse ne sont plus modifiables depuis cet écran.');
        }

        $this->fillExploitationAfInstructionSectionsFromRequest($dossier, $request);
        $dossier->syncExploitationAnalysteInstructionAvisFromAfSections();
        $dossier->exploitation_analyste_instruction_avis_saved_at = now();
        $dossier->save();

        return redirect()->back()->with('success', 'Brouillon des rubriques d’analyse enregistré. Vous pourrez continuer plus tard avant de soumettre au responsable exploitation.');
    }

    /**
     * Soumission au responsable exploitation après instruction terminée (débloque avis / validation côté REXP).
     * Exige les sept rubriques d’analyse rédigées (contenu non vide hors balises HTML).
     */
    public function soumettreExploitation(Request $request, Dossier $dossier)
    {
        $this->authorizeAnalysteDossier($dossier);

        if (! $dossier->analyste_id) {
            return redirect()
                ->back()
                ->withErrors(['submission' => 'Le dossier doit avoir un analyste affecté avant soumission au responsable exploitation.']);
        }

        $user = auth()->user();
        $national = $user instanceof User && $user->isAnalysteFinancierNational();
        if (! $national && (int) $dossier->analyste_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($dossier->isInstructionSubmittedToExploitation()) {
            return redirect()->back()->with('info', 'Ce dossier a déjà été soumis au responsable exploitation par vous.');
        }

        $this->fillExploitationAfInstructionSectionsFromRequest($dossier, $request);
        $dossier->syncExploitationAnalysteInstructionAvisFromAfSections();

        if (! $dossier->canSubmitAnalysteInstructionToExploitation()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'submission' => 'Renseignez les sept rubriques de saisie (Summernote) sous « Saisie analyste financier », puis soumettez au responsable exploitation.',
                ]);
        }

        $dossier->exploitation_analyste_instruction_avis_saved_at = now();
        $dossier->exploitation_analyste_transmitted_to_exploitation_at = now();
        $dossier->exploitation_analyste_transmitted_to_exploitation_by_user_id = auth()->id();
        // Réouverture suite à rejet : on réinitialise les marqueurs de rejet (l'historique reste tracé en timeline).
        $dossier->exploitation_analyste_rejected_at = null;
        $dossier->exploitation_analyste_rejected_by_user_id = null;
        $dossier->exploitation_analyste_reject_motif = null;
        $dossier->save();

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de dossier — responsable exploitation',
            title: 'Un dossier a été transmis au responsable exploitation',
            body: "Un dossier d’instruction a été soumis par l’analyste financier.\n\nMerci de consulter le dossier : avis de crédit, validation engagements, puis transmission au pôle juridique si nécessaire.",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('respexp.dossiers.show', $dossier->token),
            event: 'submit_analyste_to_respexp'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_exploitation', 6));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()->back()->with('success', 'Dossier soumis au responsable exploitation pour validation.');
    }

    public function fetchStats(Request $request)
    {
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'avec_analyste' => (clone $query)->whereNotNull('analyste_id')->count(),
            'sans_analyste' => (clone $query)->whereNull('analyste_id')->count(),
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
        $query = $this->applyFilters($base->clone(), $filters);

        $recordsTotal = $this->baseQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('entreprise', fn ($e) => $e->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('instructionProgrammes.programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('analyste', fn ($a) => $a->where('name', 'like', "%{$search}%"));
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->with(['entreprise', 'programme', 'instructionProgrammes.programme', 'analyste', 'agence'])->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = DossierListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function parseFilters(Request $request): array
    {
        return [
            'programme_id' => $request->input('programme_id'),
            'analyste_id' => $request->input('analyste_id'),
            'entity_route' => $request->input('entity_route'),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (! empty($filters['programme_id'])) {
            $pid = $filters['programme_id'];
            $query->where(function ($q) use ($pid) {
                $q->where('programme_id', $pid)
                    ->orWhereHas('instructionProgrammes', fn ($q2) => $q2->where('programme_id', $pid));
            });
        }
        if (! empty($filters['analyste_id'])) {
            $query->where('analyste_id', $filters['analyste_id']);
        }
        if (! empty($filters['entity_route'])) {
            $query->inCurrentEntity((string) $filters['entity_route']);
        }

        return $query;
    }

    public function fetchFilterOptions()
    {
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = $this->baseQuery()->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
        $analystes = User::query()->whereIn('id', $analysteIds)->orderBy('name')->get(['id', 'name']);

        $entities = [
            ['route' => 'respexp', 'label' => 'Pôle exploitation'],
            ['route' => 'juridique', 'label' => 'Pôle juridique'],
            ['route' => 'reng', 'label' => 'Pôle engagements'],
            ['route' => 'rerx', 'label' => 'Pôle risques'],
            ['route' => 'dg', 'label' => 'Direction générale'],
        ];

        return response()->json(['programmes' => $programmes, 'analystes' => $analystes, 'entities' => $entities]);
    }

    public function getGrilleAnalyse($token)
    {
        $item = Dossier::where('token', $token)->firstOrFail();
        $this->authorizeAnalysteDossier($item);
        $item->loadMissing(['fichiersDossier.type', 'fichiersDossier.uploadedBy']);

        return view('Analyste/Dossiers/analyse_critique', compact('item'));
    }

    public function setAnalyse()
    {
        // dd(request()->all());
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $this->authorizeAnalysteDossier(Dossier::query()->find($dossier_id));
        $data = [];
        if ($sequence == 1) {
            $data = ['donnees_generales' => $content];
        }
        if ($sequence == 2) {
            $data = ['analyse_ensemble' => $content];
        }
        if ($sequence == 3) {
            $data = ['analyse_financiere' => $content];
        }
        if ($sequence == 4) {
            $data = ['appuis' => $content];
        }
        if ($sequence == 5) {
            $data = ['analyse_risque' => $content];
        }
        if ($sequence == 6) {
            $data = ['analyse_rentabilite' => $content];
        }
        if ($sequence == 7) {
            $data = ['conclusions_analyste' => $content];
        }

        // dd($data);

        $data['instruction_grille_last_edited_at'] = now();
        $data['instruction_grille_last_edited_by_user_id'] = auth()->id();

        Dossier::updateOrCreate(['id' => $dossier_id], $data);

        return redirect()->back();

    }

    public function loadDsf(Request $request)
    {

        $dossier_id = $request->dossier_id;
        $this->authorizeAnalysteDossier(Dossier::query()->find($dossier_id));
        // $filename = $request->file('upload')->getClientOriginalName();
        $getfilePath = $request->file('upload')->getRealPath();
        $client = new Client;

        $msgServiceIndisponible = 'Le service d’analyse des fichiers DSF est momentanément indisponible : le microservice chargé du traitement du fichier ne répond pas (il est probablement arrêté ou inaccessible). '
            .'Veuillez réessayer plus tard ou contacter l’administrateur technique pour vérifier que ce service est bien démarré.';

        $msgFichierInvalide = 'Le fichier transmis ne correspond pas au format ou à la structure attendue par le service d’analyse DSF. '
            .'Vérifiez l’extension, le modèle de classeur (feuilles, colonnes) et que les données respectent le gabarit prévu. '
            .'Si besoin, demandez un modèle ou une procédure d’import à votre référent métier.';

        $msgErreurTraitementServeur = 'Une erreur s’est produite lors du traitement du fichier sur le service d’analyse DSF (réponse du microservice). '
            .'Ce message est distinct d’une simple coupure réseau : réessayez plus tard ou contactez l’administrateur technique si le problème persiste.';

        try {
            $resp = $client->request('POST', 'http://localhost:8080/dossier', [
                'multipart' => [
                    [
                        'name' => 'upload',
                        'contents' => fopen($getfilePath, 'r'),
                    ],
                    [
                        'name' => 'dossier_id',
                        'contents' => $dossier_id,
                    ],
                    [
                        'name' => 'annee',
                        'contents' => $request->annee,
                    ],
                ],
            ]);

            $data = $resp->getBody()->getContents();
            $inds = json_decode($data, true);
            if (! is_array($inds)) {
                Session::flash('error', $msgFichierInvalide);

                return back();
            }
            if ($this->dsfResponseIndiqueFichierInvalide($inds)) {
                Session::flash('error', $msgFichierInvalide);

                return back();
            }
            foreach ($inds as $ind) {
                IndicateurFinancier::updateOrCreate(
                    ['dossier_id' => $dossier_id, 'annee' => $ind['annee']], $ind
                );
            }

            Session::flash('success', 'Enregistrement effectué avec succès!');
        } catch (ConnectException $e) {
            Session::flash('error', $msgServiceIndisponible);
        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            if (in_array($status, [400, 406, 415, 422], true)) {
                Session::flash('error', $msgFichierInvalide);
            } elseif ($status === 413) {
                Session::flash('error', 'Le fichier est trop volumineux pour être accepté par le service d’analyse. Réduisez la taille ou fractionnez les données, puis réessayez.');
            } elseif ($status >= 500 && $status < 600) {
                Session::flash('error', $msgErreurTraitementServeur);
            } elseif ($status >= 400 && $status < 500) {
                Session::flash('error', $msgFichierInvalide);
            } else {
                Session::flash('error', 'Le service d’analyse des fichiers DSF n’a pas pu traiter votre demande. Réessayez ou contactez l’administrateur technique.');
            }
        } catch (GuzzleException $e) {
            Session::flash('error', 'Le service d’analyse des fichiers DSF n’a pas pu traiter votre demande (échange réseau interrompu ou délai dépassé). '
                .'Vérifiez que le microservice est disponible, puis réessayez.');
        }

        return back();
    }

    /**
     * Réponse JSON du microservice signalant un rejet de fichier (format / structure) plutôt qu’une liste d’indicateurs.
     *
     * @param  array<string, mixed>  $payload
     */
    private function dsfResponseIndiqueFichierInvalide(array $payload): bool
    {
        if ($payload === []) {
            return false;
        }
        if (array_is_list($payload)) {
            return false;
        }
        if (array_key_exists('error', $payload) || array_key_exists('errors', $payload)) {
            return true;
        }
        if (array_key_exists('success', $payload) && $payload['success'] === false) {
            return true;
        }

        return false;
    }

    public function show($token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
                'gestionnaire',
                'analyste',
                'agence',
                'exploitationAnalysteAssignedBy',
                'exploitationAnalysteTransmittedToExploitationBy',
                'exploitationAnalysteRejectedBy',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
                'exploitationAvisCreditUser',
                'exploitationEngagementsDecisionUser',
                'instructionCaTransmittedToExploitationBy',
                'juridiqueInstructionSubmittedBy',
                'juridiqueAnalysteUser',
                'juridiqueAnalysteAssignedBy',
                'juridiqueAnalysteSubmittedToRejuBy',
                'juridiqueSubmittedToEngagementsBy',
                'rengAnalysteCreditUser',
                'rengAnalysteCreditAssignedBy',
                'rengAnalysteCreditSubmittedBy',
                'rengSubmittedToRisquesBy',
                'rerxAnalysteRisquesUser',
                'rerxAnalysteRisquesAssignedBy',
                'rerxAnalysteRisquesSubmittedBy',
                'rerxSubmittedToDirectionBy',
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();

        $this->authorizeAnalysteDossier($dossier);

        $presented = app(DossierInstructionShowPresenter::class)->presentForDossier($dossier);
        $dossier = $presented['item'];
        $criteres = $presented['criteres'];
        $indicateurs = $presented['indicateurs'];
        $sme = $presented['sme'];
        $banques = $presented['banques'];
        $engagementGridUrl = $presented['engagementGridUrl'];
        $instructionConsultation = $presented['instructionConsultation'];
        $fichierTypes = $presented['fichierTypes'];

        $space = [
            'route' => 'analyste',
            'title' => 'Analyste financier',
        ];
        $readonly = false;
        $piecesModalId = 'dossierPieceUploadModal_analyste';
        $exploitationSteps = $dossier->exploitationWorkflowSteps();

        $delegation = app(\App\Services\InstructionDelegationService::class);
        $canCloseInstruction = $delegation->userCanCloseInstruction(auth()->user(), $dossier);
        $instructionClosureRuleDescription = $delegation->describeRuleForInstructionClosure($dossier);
        $instructionClosureStatutLabel = $delegation->instructionClosureStatutLabel($dossier);

        $analystesExploitation = collect();
        $analystesJuridique = collect();
        $analystesCredit = collect();
        $analystesRisques = collect();
        $respexpInstructionLocked = false;

        return view('RoleSpace.dossiers.show', compact(
            'space',
            'dossier',
            'criteres',
            'indicateurs',
            'sme',
            'banques',
            'engagementGridUrl',
            'instructionConsultation',
            'fichierTypes',
            'readonly',
            'piecesModalId',
            'exploitationSteps',
            'canCloseInstruction',
            'instructionClosureRuleDescription',
            'instructionClosureStatutLabel',
            'analystesExploitation',
            'analystesJuridique',
            'analystesCredit',
            'analystesRisques',
            'respexpInstructionLocked',
        ));
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->first();
        $this->authorizeAnalysteDossier($dossier);

        return $this->completeDossierPieceUpload($request, $dossier, 'analyste.dossiers.show', $dossier);
    }

    private function fillExploitationAfInstructionSectionsFromRequest(Dossier $dossier, Request $request): void
    {
        foreach (Dossier::exploitationAfInstructionSectionColumns() as $column) {
            $dossier->{$column} = (string) $request->input($column, $dossier->{$column} ?? '');
        }
    }
}
