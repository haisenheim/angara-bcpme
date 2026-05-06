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
        $items = $this->openProspectsQuery()->with('agence')->orderBy('prospect_submitted_at', 'asc')->get();

        return view('Prospects.review_index', [
            'title' => 'Prospects — avis juridique',
            'role' => 'juridique',
            'items' => $items,
            'exportRoute' => route('juridique.prospects.export'),
        ]);
    }

    public function indexConformite()
    {
        $items = $this->openProspectsQuery()->with('agence')->orderBy('prospect_submitted_at', 'asc')->get();

        return view('Prospects.review_index', [
            'title' => 'Prospects — avis conformité',
            'role' => 'conformite',
            'items' => $items,
            'listMode' => 'open',
            'exportRoute' => route('conformite.prospects.export'),
        ]);
    }

    public function indexConformiteTreated()
    {
        $items = $this->closedProspectsQuery()->with('agence')->orderByRaw('COALESCE(promu_client_at, prospect_rejected_at) DESC')->get();

        return view('Prospects.review_index', [
            'title' => 'Dossiers traités — avis conformité',
            'role' => 'conformite',
            'items' => $items,
            'listMode' => 'treated',
            'exportRoute' => null,
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
        $item = $this->findProspectForReview($token);
        if (! $item) {
            abort(404);
        }
        [$item, $mr, $checklist] = $this->prepareReviewPayload($item);

        $avisLocked = $item->isProspectAvisCircuitClosed();

        return view('Prospects.review_show', [
            'role' => 'juridique',
            'item' => $item,
            'mr' => $mr,
            'checklist' => $checklist,
            'avisLocked' => $avisLocked,
            'canEditAvis' => ! $avisLocked,
        ]);
    }

    public function showConformite(string $token)
    {
        $item = $this->findProspectForReview($token);
        if (! $item) {
            abort(404);
        }
        [$item, $mr, $checklist] = $this->prepareReviewPayload($item);

        $avisLocked = $item->isProspectAvisCircuitClosed();

        return view('Prospects.review_show', [
            'role' => 'conformite',
            'item' => $item,
            'mr' => $mr,
            'checklist' => $checklist,
            'avisLocked' => $avisLocked,
            'canEditAvis' => ! $avisLocked,
        ]);
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
