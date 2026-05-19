<?php

namespace App\Services;

use App\Helpers\DossierHelper;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\FichierType;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;

/**
 * Données nécessaires à l’affichage du dossier d’instruction (grille de notation, etc.)
 * — partagé entre l’espace analyste et le responsable exploitation (lecture seule).
 *
 * NB : depuis la refonte 2026-05 de la grille des engagements,
 * le détail des engagements n'est plus injecté ici. La consultation se fait
 * via la page dédiée {@see \App\Http\Controllers\Engagement\EngagementController}.
 */
class DossierInstructionShowPresenter
{
    /**
     * @return array{item: Dossier, indicateurs: \Illuminate\Support\Collection, indicateurReference: ?IndicateurFinancier, criteres: array, noteFinale: ?int, sme: mixed, banques: \Illuminate\Support\Collection, engagements: array<int, mixed>, instructionConsultation: array, fichierTypes: \Illuminate\Support\Collection, engagementGridUrl: string}
     */
    public function presentForDossier(Dossier $item): array
    {
        $item->loadMissing([
            'fichiersDossier.type',
            'fichiersDossier.uploadedBy',
            'entreprise',
            'indicateurs',
            'reponses',
        ]);

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

        $indicateurs = IndicateurFinancier::query()
            ->where('dossier_id', $item->id)
            ->orderByDesc('annee')
            ->get();
        $banques = Banque::all();
        $notation = DossierHelper::resolveInstructionNotation($item);
        $noteFinale = $notation['note_finale'];
        $sme = $notation['sme'];
        $smeMentionEtDescription = DossierHelper::smeMentionEtDescription($sme);
        $indicateurReference = $indicateurs->first();

        $instructionConsultation = app(InstructionDossierConsultationService::class)->build($item);

        $engagementGridUrl = $item->entreprise
            ? route('engagements.show', $item->entreprise->token)
            : '#';

        return [
            'item' => $item,
            'indicateurs' => $indicateurs,
            'indicateurReference' => $indicateurReference,
            'criteres' => $criteres->values()->all(),
            'noteFinale' => $noteFinale,
            'sme' => $sme,
            'smeMention' => $smeMentionEtDescription['mention'],
            'smeDescription' => $smeMentionEtDescription['description'],
            'banques' => $banques,
            'engagements' => [],
            'engagementGridUrl' => $engagementGridUrl,
            'instructionConsultation' => $instructionConsultation,
            'fichierTypes' => FichierType::query()->orderBy('name')->get(['id', 'name']),
        ];
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
