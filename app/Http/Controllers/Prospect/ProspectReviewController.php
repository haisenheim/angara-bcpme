<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\QuestionSousCritere;
use App\Services\AnalyseCritiqueService;
use App\Services\ProspectEntrepriseTableExportService;
use App\Services\TableDocumentExportService;
use App\Services\WorkflowEmailNotificationService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class ProspectReviewController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService) {}

    public function indexJuridique()
    {
        return $this->renderReviewList('juridique', 'open', 'Prospects — avis juridique',
            $this->openProspectsQuery()->orderBy('prospect_submitted_at', 'asc'), route('juridique.prospects.export'));
    }

    public function indexConformite()
    {
        return $this->renderReviewList('conformite', 'open', 'Prospects — avis conformité',
            $this->openProspectsQuery()->orderBy('prospect_submitted_at', 'asc'), route('conformite.prospects.export'));
    }

    public function indexConformiteTreated()
    {
        return $this->renderReviewList('conformite', 'treated', 'Dossiers traités — avis conformité',
            $this->closedProspectsQuery()->orderByRaw('COALESCE(promu_client_at, prospect_rejected_at) DESC'), null);
    }

    /**
     * Liste Inertia des prospects soumis à avis (juridique / conformité).
     */
    private function renderReviewList(string $role, string $mode, string $title, $query, ?string $exportUrl): \Inertia\Response
    {
        $avisField = $role === 'juridique' ? 'juridique_avis_at' : 'conformite_avis_at';
        $items = $query->with('agence')->paginate(25)->withQueryString();

        $table = \App\Support\Inertia\DataTable::make($items)
            ->columns([
                ['key' => 'name', 'label' => 'Dénomination'],
                ['key' => 'soumis', 'label' => 'Soumis le'],
                ['key' => 'agence', 'label' => 'Agence'],
                ['key' => 'statut', 'label' => 'Statut'],
            ])
            ->row(fn (Entreprise $e) => [
                'href' => route("$role.prospects.show", $e->token),
                'cells' => [
                    'name' => ['title' => $e->name, 'subtitle' => $e->rccm],
                    'soumis' => $e->prospect_submitted_at ? \Carbon\Carbon::parse($e->prospect_submitted_at)->format('d/m/Y H:i') : null,
                    'agence' => $e->agence?->name,
                    'statut' => match (true) {
                        $e->promu_client_at !== null => ['badge' => 'Promu client', 'variant' => 'success'],
                        $e->prospect_rejected_at !== null => ['badge' => 'Refusé', 'variant' => 'danger'],
                        $e->{$avisField} !== null => ['badge' => 'Avis rendu — modifiable', 'variant' => 'success'],
                        default => ['badge' => 'En attente de votre avis', 'variant' => 'warning'],
                    },
                ],
            ])
            ->empty($mode === 'treated' ? 'Aucun dossier traité.' : 'Aucun prospect en attente d\'avis.');
        if ($exportUrl) {
            $table->export('Excel', $exportUrl.'?format=xlsx', 'table_view')->export('PDF', $exportUrl.'?format=pdf', 'picture_as_pdf');
        }

        return \Inertia\Inertia::render('Entreprises/Index', [
            'title' => $title,
            'subtitle' => $mode === 'treated'
                ? 'Prospects dont le circuit est clos par le chef d\'agence (lecture seule).'
                : 'Prospects soumis par les gestionnaires, en attente de décision du chef d\'agence.',
            'countLabel' => 'prospect(s)',
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route("$role.dashboard")],
                ['label' => $title],
            ],
        ]);
    }

    public function exportOpenProspectsJuridique(Request $request)
    {
        return $this->exportOpenProspectsList($request, 'juridique', 'juridique-prospects-circuit', 'Juridique — prospects en circuit d\'avis');
    }

    public function exportOpenProspectsConformite(Request $request)
    {
        return $this->exportOpenProspectsList($request, 'conformite', 'conformite-prospects-circuit', 'Conformité — prospects en circuit d\'avis');
    }

    private function exportOpenProspectsList(Request $request, string $role, string $filenameSlug, string $documentTitle): mixed
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $items = $this->openProspectsQuery()->with('agence')->orderBy('prospect_submitted_at', 'asc')->get();
        $rows = ProspectEntrepriseTableExportService::rowsReviewOpen($items, $role);

        return TableDocumentExportService::downloadFormatted(
            $rows,
            ProspectEntrepriseTableExportService::headersReviewOpen($role),
            $format,
            $filenameSlug,
            $documentTitle,
            'Liste des dossiers soumis (circuit ouvert)',
        );
    }

    public function showJuridique(string $token)
    {
        return $this->renderReview('juridique', $token);
    }

    public function showConformite(string $token)
    {
        return $this->renderReview('conformite', $token);
    }

    public function storeJuridique(Request $request, string $token)
    {
        $item = $this->findProspectForReview($token);
        if (! $item) {
            abort(404);
        }
        if ($item->isProspectAvisCircuitClosed()) {
            Session::flash('info', 'Ce dossier est clos par le chef d\'agence : aucune modification d\'avis n\'est possible.');

            return redirect()->route('juridique.prospects.show', $token);
        }

        $data = $request->validate([
            'juridique_avis' => 'required|string|max:20000',
        ]);

        $item->juridique_avis = $data['juridique_avis'];
        $item->juridique_avis_at = now();
        $item->juridique_avis_user_id = auth()->id();
        $item->save();
        $this->analyseCritiqueService->syncProspectWorkflow($item);

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForEntreprise($item);

        // Informer le gestionnaire (ou créateur) qu'un avis a été saisi
        $gestionnaire = null;
        if ($item->gestionnaire_id) {
            $gestionnaire = User::query()->whereKey((int) $item->gestionnaire_id)->first();
        }
        if (! $gestionnaire && $item->user_id) {
            $gestionnaire = User::query()->whereKey((int) $item->user_id)->first();
        }
        if ($gestionnaire) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect — avis juridique disponible',
                title: 'Avis juridique enregistré',
                body: "L’avis juridique a été enregistré sur un prospect soumis.\n\nVous pouvez consulter la fiche et poursuivre le circuit.",
                ctaLabel: 'Ouvrir le prospect',
                ctaUrl: route('gestionnaire.entreprises.show', $item->token),
                event: 'prospect_juridique_avis_saved'
            );
            $mailer->notifyUser($gestionnaire, auth()->user(), $payload, $ctx);
        }

        // Si les deux avis sont disponibles, notifier le chef d'agence pour décision
        if ($item->juridique_avis_at && $item->conformite_avis_at && ! $item->promu_client_at && ! $item->prospect_rejected_at) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect — décision chef d’agence requise',
                title: 'Prospect prêt pour décision',
                body: "Les avis juridique et conformité sont disponibles.\n\nMerci de consulter le prospect et de décider (promotion client ou refus).",
                ctaLabel: 'Ouvrir le prospect',
                ctaUrl: route('ca.workflow.prospects.show', $item->token),
                event: 'prospect_ready_for_ca_decision'
            );
            $recipients = $mailer->recipientsByRole(
                (int) config('angara.role_chef_agence', 15),
                $item->agence_id ? (int) $item->agence_id : null
            );
            $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);
        }

        Session::flash('success', 'Avis juridique enregistré le '.now()->format('d/m/Y à H:i').'.');

        return redirect()->route('juridique.prospects.show', $token);
    }

    public function storeConformite(Request $request, string $token)
    {
        $item = $this->findProspectForReview($token);
        if (! $item) {
            abort(404);
        }
        if ($item->isProspectAvisCircuitClosed()) {
            Session::flash('info', 'Ce dossier est clos par le chef d\'agence : aucune modification d\'avis n\'est possible.');

            return redirect()->route('conformite.prospects.show', $token);
        }

        $data = $request->validate([
            'conformite_avis' => 'required|string|max:20000',
        ]);

        $item->conformite_avis = $data['conformite_avis'];
        $item->conformite_avis_at = now();
        $item->conformite_avis_user_id = auth()->id();
        $item->save();
        $this->analyseCritiqueService->syncProspectWorkflow($item);

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForEntreprise($item);

        $gestionnaire = null;
        if ($item->gestionnaire_id) {
            $gestionnaire = User::query()->whereKey((int) $item->gestionnaire_id)->first();
        }
        if (! $gestionnaire && $item->user_id) {
            $gestionnaire = User::query()->whereKey((int) $item->user_id)->first();
        }
        if ($gestionnaire) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect — avis conformité disponible',
                title: 'Avis conformité enregistré',
                body: "L’avis conformité a été enregistré sur un prospect soumis.\n\nVous pouvez consulter la fiche et poursuivre le circuit.",
                ctaLabel: 'Ouvrir le prospect',
                ctaUrl: route('gestionnaire.entreprises.show', $item->token),
                event: 'prospect_conformite_avis_saved'
            );
            $mailer->notifyUser($gestionnaire, auth()->user(), $payload, $ctx);
        }

        if ($item->juridique_avis_at && $item->conformite_avis_at && ! $item->promu_client_at && ! $item->prospect_rejected_at) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect — décision chef d’agence requise',
                title: 'Prospect prêt pour décision',
                body: "Les avis juridique et conformité sont disponibles.\n\nMerci de consulter le prospect et de décider (promotion client ou refus).",
                ctaLabel: 'Ouvrir le prospect',
                ctaUrl: route('ca.workflow.prospects.show', $item->token),
                event: 'prospect_ready_for_ca_decision'
            );
            $recipients = $mailer->recipientsByRole(
                (int) config('angara.role_chef_agence', 15),
                $item->agence_id ? (int) $item->agence_id : null
            );
            $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);
        }

        Session::flash('success', 'Avis conformité enregistré le '.now()->format('d/m/Y à H:i').'.');

        return redirect()->route('conformite.prospects.show', $token);
    }

    /**
     * Fiche prospect (page Inertia Entreprises/Show) avec l'action « avis » du métier.
     */
    private function renderReview(string $role, string $token): \Inertia\Response
    {
        $item = $this->findProspectForReview($token);
        if (! $item) {
            abort(404);
        }
        $item->load(\App\Services\Inertia\EntrepriseSheetPresenter::RELATIONS);

        $sheet = app(\App\Services\Inertia\EntrepriseSheetPresenter::class)->present(
            $item, $this->buildQuestionnaireResults($item), $item->piecesExigiblesChecklist(),
            ['routePrefix' => $role]
        );

        $field = $role === 'juridique' ? 'juridique_avis' : 'conformite_avis';
        $label = $role === 'juridique' ? 'avis juridique' : 'avis conformité';
        $workflowActions = [];
        if (! $item->isProspectAvisCircuitClosed()) {
            $workflowActions[] = [
                'key' => 'avis',
                'label' => $item->{$field} ? 'Modifier mon '.$label : 'Saisir mon '.$label,
                'icon' => 'rate_review',
                'variant' => 'primary',
                'title' => ucfirst($label),
                'description' => 'L\'avis reste modifiable jusqu\'à la décision du chef d\'agence.',
                'fields' => [['name' => $field, 'label' => 'Avis', 'type' => 'richtext', 'required' => true, 'value' => $item->{$field}]],
                'submits' => [['label' => 'Enregistrer l\'avis', 'url' => route("$role.prospects.avis", $item->token), 'variant' => 'primary']],
                'wide' => true,
            ];
        }

        return \Inertia\Inertia::render('Entreprises/Show', $sheet + [
            'links' => [],
            'actions' => [],
            'workflowActions' => $workflowActions,
            'notice' => $item->isProspectAvisCircuitClosed()
                ? 'Circuit clos par le chef d\'agence : les avis ne sont plus modifiables.'
                : null,
            'initialTab' => 'avis',
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route("$role.dashboard")],
                ['label' => 'Prospects', 'href' => route("$role.prospects.index")],
                ['label' => \Illuminate\Support\Str::limit($item->name, 40)],
            ],
        ]);
    }

    /**
     * Prospects encore dans le circuit chef d'agence (modifiables par les deux métiers).
     */
    private function openProspectsQuery()
    {
        return Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('prospect_rejected_at')
            ->whereNull('promu_client_at');
    }

    /**
     * Prospects dont le circuit chef d'agence est clos (promotion ou refus).
     */
    private function closedProspectsQuery()
    {
        return Entreprise::query()
            ->whereNotNull('prospect_submitted_at')
            ->where(function ($q) {
                $q->whereNotNull('promu_client_at')
                    ->orWhereNotNull('prospect_rejected_at');
            });
    }

    /**
     * Fiche consultable : circuit ouvert, ou clos (lecture seule après décision chef d'agence).
     */
    private function findProspectForReview(string $token): ?Entreprise
    {
        $query = Entreprise::query()
            ->where('token', $token)
            ->where(function ($q) {
                // Circuit ouvert (soumis) ou clos (promotion / rejet).
                $q->whereNotNull('prospect_submitted_at');
                if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'prospect_rejected_at')) {
                    $q->orWhereNotNull('prospect_rejected_at');
                }
                $q->orWhereNotNull('promu_client_at');
            });

        return $query->first();
    }

    /**
     * @return array{0: Entreprise, 1: \Illuminate\Support\Collection, 2: \Illuminate\Support\Collection}
     */
    private function prepareReviewPayload(Entreprise $item): array
    {
        $item->load([
            'juridiqueAvisUser',
            'conformiteAvisUser',
            'promuClientUser',
            'prospectRejectedUser',
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
            'village',
            'quartier',
            'tiers.person',
            'tiers.company.produit',
        ]);

        return [$item, $this->buildQuestionnaireResults($item), $item->piecesExigiblesChecklist()];
    }

    private function buildQuestionnaireResults(Entreprise $item)
    {
        $reponses = $item->reponses()->with(['question', 'choice'])->get();

        return $reponses->groupBy('critere_id')->map(function ($items, $critereId) {
            $critere = InstructionCritere::find($critereId);

            return [
                'critere' => $critere,
                'items' => $items->groupBy('sous_critere_id')->map(function ($group, $sousCritereId) {
                    $sousCritere = QuestionSousCritere::find($sousCritereId);

                    return [
                        'sous_critere' => $sousCritere,
                        'items' => $group,
                    ];
                }),
            ];
        });
    }
}
