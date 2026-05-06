<?php

namespace App\Services;

use App\Models\Dossier;
use Carbon\Carbon;

/**
 * Document « dossier d’analyse critique » : aligné sur la section
 * Analyse critique de l’analyste financier (rubriques AF affichées séparément).
 */
class InstructionAnalyseCritiqueDossierDocumentService
{
    /**
     * @return array{
     *   item: Dossier,
     *   saisies_meta: array{edited_at: ?Carbon, edited_by: null},
     *   analyste: ?\App\Models\User,
     *   has_analyste_document: bool
     * }
     */
    public function build(Dossier $dossier): array
    {
        $dossier->loadMissing([
            'entreprise',
            'programme',
            'instructionProgrammes.programme',
            'analyste.role',
        ]);

        $item = $dossier;

        return [
            'item' => $item,
            'saisies_meta' => [
                'edited_at' => $item->exploitation_analyste_instruction_avis_saved_at,
                'edited_by' => null,
            ],
            'analyste' => $item->analyste,
            'has_analyste_document' => $item->hasExploitationAnalysteInstructionAvisSubstance(),
        ];
    }
}
