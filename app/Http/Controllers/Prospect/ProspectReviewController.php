<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\QuestionSousCritere;
use App\Services\AnalyseCritiqueService;
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
        ]);
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
            ->whereNotNull('prospect_submitted_at');
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
            ->whereNotNull('prospect_submitted_at')
            ->where(function ($q) {
                $q->where('prospect', true)
                    ->orWhereNotNull('promu_client_at');
                if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'prospect_rejected_at')) {
                    $q->orWhereNotNull('prospect_rejected_at');
                }
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
