<?php

namespace App\Services;

use App\Helpers\DossierHelper;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use App\Models\Instruction\IndicateurFinancier;

/**
 * Données nécessaires à l’affichage du dossier d’instruction (grille de notation, engagements, etc.)
 * — partagé entre l’espace analyste et le responsable exploitation (lecture seule).
 */
class DossierInstructionShowPresenter
{
    /**
     * @return array{item: Dossier, indicateurs: \Illuminate\Support\Collection, criteres: array, sme: mixed, banques: \Illuminate\Support\Collection, engagements: array<int, mixed>}
     */
    public function presentForDossier(Dossier $item): array
    {
        $engagements = Engagement::where('parent_id', 0)->get();
        $data = [];
        foreach ($engagements as $eng) {
            $data[] = $this->parseEngagement($eng, 1);
        }

        $criteres = Critere::all();
        $id = $item->id;
        $criteres = $criteres->map(function ($critere) use ($id) {
            $critere->souscriteres = $critere->sousCriteres->map(function ($souscritere) use ($id) {
                $souscritere->reponse = $souscritere->reponses->where('dossier_id', $id)->first();

                return $souscritere;
            });

            return $critere;
        });

        $criteres = $criteres->map(fn ($ct) => $this->parseCriterePourGrille($ct));

        $indicateurs = IndicateurFinancier::where('dossier_id', $item->id)->get();
        $banques = Banque::all();
        $sme = DossierHelper::getSme($item->note);

        return [
            'item' => $item,
            'indicateurs' => $indicateurs,
            'criteres' => $criteres->values()->all(),
            'sme' => $sme,
            'banques' => $banques,
            'engagements' => $data,
        ];
    }

    private function parseEngagement(Engagement $eng, int $entrepriseId): array
    {
        $data = [
            'id' => $eng->id,
            'name' => $eng->name,
            'montant' => $eng->montant ?? 0,
            'encours_montant' => $eng->encours_montant ?? 0,
            'encours_impaye' => $eng->encours_impaye ?? 0,
            'sollicite_montant' => $eng->sollicite_montant ?? 0,
            'parent_id' => $eng->parent_id,
            'is_title' => $eng->is_title,
            'is_leaf' => $eng->is_leaf,
            'niveau' => $eng->niveau,
        ];
        if ($data['is_leaf']) {
            $elts = EngagementEntreprise::where('engagement_id', $eng->id)->where('entreprise_id', $entrepriseId)->get();
            $data['encours_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + $item->encours_montant;
            }, 0);
            $data['sollicite_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + $item->sollicite_montant;
            }, 0);
            $data['encours_impaye'] = $elts->reduce(function ($carry, $item) {
                return $carry + $item->encours_impaye;
            }, 0);
            $data['elts'] = $elts;

        } else {
            $data['children'] = $eng->children->map(function ($child) use ($entrepriseId) {
                return $this->parseEngagement($child, $entrepriseId);
            });
            foreach ($data['children'] as $child) {
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
            }
        }

        return $data;
    }

    private function parseCriterePourGrille(Critere $critere): array
    {
        $dsc = [];
        $note = 0;
        foreach ($critere->souscriteres as $sc) {
            $r = $sc->reponse;
            $ch = $r?->choice;
            if ($r) {
                $note += $r->value;
            }
            $dsc[] = [
                'id' => $sc->id,
                'name' => $sc->name,
                'critereId' => $sc->critere_id,
                'sequence' => $sc->sequence,
                'default' => $sc->default,
                'note' => $r ? $r->note : 0,
                'reponse' => $r ? [
                    'id' => $r->id,
                    'dossierId' => $r->dossier_id,
                    'critereId' => $r->critere_id,
                    'choiceId' => $r->choice_id,
                    'note' => $r->note,
                    'choice' => [
                        'id' => $ch->id,
                        'valeur' => $ch->valeur,
                        'note' => $ch->note,
                        'critereId' => $sc->critere_id,
                    ],

                ] : [],
            ];
        }

        return [
            'id' => $critere->id,
            'name' => $critere->name,
            'note' => $note,
            'souscriteres' => $dsc,
        ];
    }
}
