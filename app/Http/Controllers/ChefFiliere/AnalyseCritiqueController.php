<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Services\AnalyseCritiqueService;

class AnalyseCritiqueController extends Controller
{
    use AuthorizesAgenceEntreprise;

    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService) {}

    public function show(string $token)
    {
        $item = $this->entrepriseForAgence($token);
        $item->load([
            'agence',
            'dossierAnalyseCritique.avis.emisPar',
            'dossiers.programme',
            'dossierEntreeRelation.programmeSelections.programme',
        ]);

        $this->analyseCritiqueService->syncProspectWorkflow($item);
        if ($item->dossierEntreeRelation) {
            $this->analyseCritiqueService->syncChefFiliereQualification($item, $item->dossierEntreeRelation);
        }
        foreach ($item->dossiers as $dossier) {
            $this->analyseCritiqueService->syncInstructionDossier($dossier, 'Dossier d\'instruction rattaché au client.');
        }

        $item->refresh()->load(['dossierAnalyseCritique.avis.emisPar', 'dossiers.programme', 'dossierEntreeRelation.programmeSelections.programme']);

        return view('ChefFiliere.Companies.analyse_critique', [
            'item' => $item,
            'analyseCritique' => $item->dossierAnalyseCritique,
        ]);
    }
}
