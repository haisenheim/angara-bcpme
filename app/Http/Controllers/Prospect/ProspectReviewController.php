<?php

namespace App\Http\Controllers\Prospect;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\QuestionSousCritere;
use App\Services\AnalyseCritiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProspectReviewController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService)
    {
    }

    public function indexJuridique()
    {
        $items = $this->pendingForJuridique()->with('agence')->orderBy('prospect_submitted_at', 'asc')->get();

        return view('Prospects.review_index', [
            'title' => 'Prospects — avis juridique',
            'role' => 'juridique',
            'items' => $items,
        ]);
    }

    public function indexConformite()
    {
        $items = $this->pendingForConformite()->with('agence')->orderBy('prospect_submitted_at', 'asc')->get();

        return view('Prospects.review_index', [
            'title' => 'Prospects — avis conformité',
            'role' => 'conformite',
            'items' => $items,
        ]);
    }

    public function showJuridique(string $token)
    {
        $item = $this->findSubmittedProspect($token);
        if (! $item) {
            abort(404);
        }
        [$item, $mr, $checklist] = $this->prepareReviewPayload($item);

        return view('Prospects.review_show', [
            'role' => 'juridique',
            'item' => $item,
            'mr' => $mr,
            'checklist' => $checklist,
            'pending' => $item->juridique_avis_at === null,
        ]);
    }

    public function showConformite(string $token)
    {
        $item = $this->findSubmittedProspect($token);
        if (! $item) {
            abort(404);
        }
        [$item, $mr, $checklist] = $this->prepareReviewPayload($item);

        return view('Prospects.review_show', [
            'role' => 'conformite',
            'item' => $item,
            'mr' => $mr,
            'checklist' => $checklist,
            'pending' => $item->conformite_avis_at === null,
        ]);
    }

    public function storeJuridique(Request $request, string $token)
    {
        $item = $this->findSubmittedProspect($token);
        if (! $item) {
            abort(404);
        }
        if ($item->juridique_avis_at !== null) {
            Session::flash('info', 'Un avis juridique a déjà été enregistré pour ce prospect.');

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

        return redirect()->route('juridique.prospects.index');
    }

    public function storeConformite(Request $request, string $token)
    {
        $item = $this->findSubmittedProspect($token);
        if (! $item) {
            abort(404);
        }
        if ($item->conformite_avis_at !== null) {
            Session::flash('info', 'Un avis conformité a déjà été enregistré pour ce prospect.');

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

        return redirect()->route('conformite.prospects.index');
    }

    private function pendingForJuridique()
    {
        return Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('juridique_avis_at');
    }

    private function pendingForConformite()
    {
        return Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('conformite_avis_at');
    }

    private function findSubmittedProspect(string $token): ?Entreprise
    {
        return Entreprise::where('token', $token)
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->first();
    }

    /**
     * @return array{0: Entreprise, 1: \Illuminate\Support\Collection, 2: \Illuminate\Support\Collection}
     */
    private function prepareReviewPayload(Entreprise $item): array
    {
        $item->load([
            'juridiqueAvisUser',
            'conformiteAvisUser',
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
