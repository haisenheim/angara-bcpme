<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AnalyseCritiqueController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService)
    {
    }

    public function show(string $token)
    {
        $item = Entreprise::where('token', $token)
            ->with([
                'agence',
                'dossierAnalyseCritique.avis.emisPar',
                'dossiers.programme',
                'dossierEntreeRelation.programmeSelections.programme',
            ])
            ->firstOrFail();
        $this->authorizeEntreprise($item);

        $this->analyseCritiqueService->syncProspectWorkflow($item);
        if ($item->dossierEntreeRelation) {
            $this->analyseCritiqueService->syncChefFiliereQualification($item, $item->dossierEntreeRelation);
        }
        foreach ($item->dossiers as $dossier) {
            $this->analyseCritiqueService->syncInstructionDossier($dossier, 'Dossier d\'instruction rattache au client.');
        }

        $item->refresh()->load(['dossierAnalyseCritique.avis.emisPar', 'dossiers.programme', 'dossierEntreeRelation.programmeSelections.programme']);

        return view('Gestionnaire.Companies.analyse_critique', [
            'item' => $item,
            'analyseCritique' => $item->dossierAnalyseCritique,
        ]);
    }

    public function update(Request $request, string $token)
    {
        $item = Entreprise::where('token', $token)->firstOrFail();
        $this->authorizeEntreprise($item);
        $analyseCritique = $this->analyseCritiqueService->ensureForEntreprise($item);

        $data = $request->validate([
            'synthese' => 'nullable|string|max:50000',
            'statut' => 'nullable|string|max:32',
        ]);

        $analyseCritique->synthese = $data['synthese'] ?? null;
        $analyseCritique->statut = $data['statut'] ?? $analyseCritique->statut;
        $analyseCritique->save();

        Session::flash('success', 'Synthese d\'analyse critique enregistree.');

        return redirect()->route('gestionnaire.entreprises.analyse-critique.show', $token);
    }

    private function authorizeEntreprise(Entreprise $entreprise): void
    {
        $userId = auth()->id();
        if ((int) $entreprise->gestionnaire_id !== (int) $userId && (int) $entreprise->user_id !== (int) $userId) {
            abort(403);
        }
    }
}
