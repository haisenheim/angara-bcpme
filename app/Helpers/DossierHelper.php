<?php

namespace App\Helpers;

use App\Models\Dossier;
use App\Models\Instruction\SmeNote;
use App\Models\Instruction\Critere;
use Illuminate\Support\Collection;

class DossierHelper
{
    /**
     * Retourne les variations des indicateurs financiers d'un dossier
     */
    public static function getVariations(Dossier $dossier): array
    {
        $indicateurs = $dossier->indicateurs;
        $variations = [];

        foreach ($indicateurs as $indicateur) {
            if (isset($indicateur['values']) && count($indicateur['values']) >= 2) {
                $lastIndex = count($indicateur['values']) - 1;
                $currentValue = $indicateur['values'][$lastIndex]['value'];
                $previousValue = $indicateur['values'][$lastIndex - 1]['value'];

                $variation = $previousValue != 0 ?
                    (($currentValue - $previousValue) / $previousValue) * 100 :
                    0;

                $variations[] = [
                    'id' => $indicateur['id'],
                    'variation' => $variation
                ];
            }
        }

        return $variations;
    }

    /**
     * Retourne l'objet SmeNote correspondant à une note donnée
     */
    public static function getSme(int $note): ?SmeNote
    {
        return SmeNote::where('note', $note)->first();
    }

    /**
     * Transforme les critères et calcule les notes
     */
    public static function transformCriteres(Collection $criteres): array
    {
        return $criteres->map(function (Critere $critere) {
            $sousCriteres = $critere->sousCriteres;
            $totalScore = 0;
            $totalPonderation = 0;

            foreach ($sousCriteres as $sousCritere) {
                $reponses = $sousCritere->reponses;
                $selectedReponse = $reponses->where('selected', true)->first();

                if ($selectedReponse) {
                    $score = $selectedReponse->note * $sousCritere->ponderation;
                    $totalScore += $score;
                    $totalPonderation += $sousCritere->ponderation;
                }
            }

            $note = $totalPonderation > 0 ? round($totalScore / $totalPonderation) : null;

            return [
                'id' => $critere->id,
                'note' => $note
            ];
        })->toArray();
    }
}
