<?php

namespace App\Helpers;

use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;
use App\Models\Instruction\SmeNote;
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
     * Indicateur financier de référence (exercice le plus récent) pour la notation Finance.
     */
    public static function referenceIndicateurFinancier(Dossier $dossier): ?IndicateurFinancier
    {
        $indicateurs = $dossier->relationLoaded('indicateurs')
            ? $dossier->indicateurs
            : $dossier->indicateurs()->get();

        if ($indicateurs->isEmpty()) {
            return null;
        }

        return $indicateurs->sortByDesc(fn (IndicateurFinancier $i) => (int) $i->annee)->first();
    }

    /**
     * Note pondérée finale du dossier d’instruction (qualitatif + finance).
     */
    public static function finalInstructionNote(Dossier $dossier): ?int
    {
        $reponses = $dossier->relationLoaded('reponses')
            ? $dossier->reponses
            : $dossier->reponses()->get();

        $nql = $reponses->sum('value');
        $ref = self::referenceIndicateurFinancier($dossier);
        $nf = $ref ? (float) ($ref->notation['note'] ?? 0) : 0.0;

        if (! $ref && $reponses->isEmpty()) {
            return null;
        }

        return (int) round($nf + $nql);
    }

    /**
     * Retourne l'objet SmeNote (notation PME) correspondant à la note finale.
     * La note est bornée à l’échelle 1–10 de la table sme_notes.
     */
    public static function getSme(int $note): ?SmeNote
    {
        if ($note < 1) {
            return null;
        }

        $lookup = min($note, 10);

        return SmeNote::query()->where('note', $lookup)->first();
    }

    /**
     * Mention et description SME (table sme_notes, clé = colonne note).
     *
     * @return array{mention: ?string, description: ?string}
     */
    public static function smeMentionEtDescription(?SmeNote $sme): array
    {
        if (! $sme) {
            return ['mention' => null, 'description' => null];
        }

        $mention = trim((string) ($sme->mention ?? ''));
        $description = trim((string) ($sme->description ?? ''));

        return [
            'mention' => $mention !== '' ? $mention : null,
            'description' => $description !== '' ? $description : null,
        ];
    }

    /** Résout la ligne sme_notes à partir de la note pondérée finale. */
    public static function resolveSmeFromNoteFinale(?int $noteFinale): ?SmeNote
    {
        if ($noteFinale === null || $noteFinale < 1) {
            return null;
        }

        return self::getSme($noteFinale);
    }

    /**
     * Note finale + notation PME déduite (comme sur la branche demo).
     *
     * @return array{note_finale: ?int, sme: ?SmeNote}
     */
    public static function resolveInstructionNotation(Dossier $dossier): array
    {
        $noteFinale = self::finalInstructionNote($dossier);

        return [
            'note_finale' => $noteFinale,
            'sme' => $noteFinale !== null ? self::getSme($noteFinale) : null,
        ];
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
