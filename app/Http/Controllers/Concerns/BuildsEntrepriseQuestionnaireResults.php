<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Entreprise;
use App\Models\Instruction\Critere;
use App\Models\QuestionSousCritere;

trait BuildsEntrepriseQuestionnaireResults
{
    protected function buildQuestionnaireResults(Entreprise $item)
    {
        $reponses = $item->reponses()->with(['question', 'choice'])->get();

        return $reponses->groupBy('critere_id')->map(function ($items, $critereId) {
            $critere = Critere::find($critereId);

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
