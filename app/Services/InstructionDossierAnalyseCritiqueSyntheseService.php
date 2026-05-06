<?php

namespace App\Services;

use App\Models\Dossier;

/**
 * Relations à charger pour la chronologie d’instruction (événements + avis intégrés).
 *
 * @see InstructionAnalyseCritiqueDossierDocumentService pour le document « analyse critique » analyste (grille + 7 zones).
 */
class InstructionDossierAnalyseCritiqueSyntheseService
{
    public function loadDossierRelationsForTimeline(Dossier $dossier): void
    {
        $dossier->loadMissing([
            'chefFiliereSubmittedToAgenceBy.role',
            'instructionAgenceValidatedBy.role',
            'instructionAgenceRejectedBy.role',
            'instructionCaTransmittedToExploitationBy.role',
            'exploitationAvisCreditUser.role',
            'exploitationEngagementsDecisionUser.role',
            'exploitationAnalysteAssignedBy.role',
            'exploitationAnalysteTransmittedToExploitationBy.role',
            'juridiqueInstructionSubmittedBy.role',
            'juridiqueAnalysteAssignedBy.role',
            'juridiqueAnalysteSubmittedToRejuBy.role',
            'juridiqueSubmittedToEngagementsBy.role',
            'rengAnalysteCreditAssignedBy.role',
            'rengAnalysteCreditSubmittedBy.role',
            'rengSubmittedToRisquesBy.role',
            'rerxAnalysteRisquesAssignedBy.role',
            'rerxAnalysteRisquesSubmittedBy.role',
            'rerxSubmittedToDirectionBy.role',
        ]);
    }
}
