<?php

namespace App\Services;

use App\Models\AnalyseCritiqueAvis;
use App\Models\Dossier;
use App\Models\DossierAnalyseCritique;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;

class AnalyseCritiqueService
{
    public function ensureForEntreprise(Entreprise $entreprise): DossierAnalyseCritique
    {
        return DossierAnalyseCritique::firstOrCreate(
            ['entreprise_id' => $entreprise->id],
            ['token' => sha1('dac-'.$entreprise->id.'-'.microtime(true))]
        );
    }

    public function syncProspectWorkflow(Entreprise $entreprise): DossierAnalyseCritique
    {
        $dossier = $this->ensureForEntreprise($entreprise);

        if ($entreprise->prospect_submitted_at) {
            $this->upsertAvis(
                $dossier,
                AnalyseCritiqueAvis::SOURCE_GESTIONNAIRE,
                'Soumission initiale du prospect',
                'Prospect soumis par le gestionnaire pour avis juridique et conformite.',
                AnalyseCritiqueAvis::ETAT_INTEGRE,
                $entreprise->prospect_submitted_at,
                $entreprise->gestionnaire_id ?: $entreprise->user_id,
                null,
                10
            );
        }

        if ($entreprise->juridique_avis) {
            $this->upsertAvis(
                $dossier,
                AnalyseCritiqueAvis::SOURCE_JURIDIQUE,
                'Avis juridique',
                $entreprise->juridique_avis,
                AnalyseCritiqueAvis::ETAT_INTEGRE,
                $entreprise->juridique_avis_at,
                $entreprise->juridique_avis_user_id,
                null,
                20
            );
        }

        if ($entreprise->conformite_avis) {
            $this->upsertAvis(
                $dossier,
                AnalyseCritiqueAvis::SOURCE_CONFORMITE,
                'Avis conformite',
                $entreprise->conformite_avis,
                AnalyseCritiqueAvis::ETAT_INTEGRE,
                $entreprise->conformite_avis_at,
                $entreprise->conformite_avis_user_id,
                null,
                30
            );
        }

        return $dossier;
    }

    public function syncChefFiliereQualification(Entreprise $entreprise, DossierEntreeRelation $eer): DossierAnalyseCritique
    {
        $dossier = $this->syncProspectWorkflow($entreprise);

        $content = trim(implode("\n\n", array_filter([
            $eer->analyse_strategique ? "Analyse strategique:\n".$eer->analyse_strategique : null,
            $eer->analyse_operationnelle ? "Analyse operationnelle:\n".$eer->analyse_operationnelle : null,
            $eer->analyse_eligibilite ? "Eligibilite:\n".$eer->analyse_eligibilite : null,
            $eer->identification_besoins ? "Identification des besoins:\n".$eer->identification_besoins : null,
            $eer->qualification_notes ? "Notes de qualification:\n".$eer->qualification_notes : null,
        ])));

        if ($content !== '') {
            $this->upsertAvis(
                $dossier,
                AnalyseCritiqueAvis::SOURCE_CHEF_FILIERE,
                'Qualification chef de filiere',
                $content,
                AnalyseCritiqueAvis::ETAT_INTEGRE,
                $eer->qualification_completed_at,
                $eer->qualification_user_id,
                null,
                40
            );
        }

        return $dossier;
    }

    public function syncChefAgenceValidation(Entreprise $entreprise, DossierEntreeRelation $eer, string $message): DossierAnalyseCritique
    {
        $dossier = $this->syncChefFiliereQualification($entreprise, $eer);

        $this->upsertAvis(
            $dossier,
            AnalyseCritiqueAvis::SOURCE_CHEF_AGENCE,
            'Validation chef d\'agence',
            $message,
            AnalyseCritiqueAvis::ETAT_INTEGRE,
            $eer->instruction_validated_at ?: $entreprise->promu_client_at,
            $eer->instruction_validated_by_user_id ?: $entreprise->promu_client_user_id,
            null,
            50
        );

        return $dossier;
    }

    /**
     * Prospect refusé par le chef d'agence (non promu client).
     */
    public function syncProspectRejetChefAgence(Entreprise $entreprise, string $message): DossierAnalyseCritique
    {
        $dossier = $this->syncProspectWorkflow($entreprise);

        $this->upsertAvis(
            $dossier,
            AnalyseCritiqueAvis::SOURCE_CHEF_AGENCE,
            'Refus chef d\'agence',
            $message,
            AnalyseCritiqueAvis::ETAT_INTEGRE,
            $entreprise->prospect_rejected_at,
            $entreprise->prospect_rejected_user_id,
            null,
            45
        );

        return $dossier;
    }

    public function syncInstructionDossier(Dossier $instructionDossier, string $message): AnalyseCritiqueAvis
    {
        $entreprise = $instructionDossier->entreprise;
        $dossier = $this->ensureForEntreprise($entreprise);

        return $this->upsertAvis(
            $dossier,
            AnalyseCritiqueAvis::SOURCE_INSTRUCTION,
            'Dossier instruction - '.($instructionDossier->programme?->name ?? 'Programme'),
            $message,
            AnalyseCritiqueAvis::ETAT_INTEGRE,
            $instructionDossier->updated_at ?: $instructionDossier->created_at,
            $instructionDossier->gestionnaire_id,
            $instructionDossier->id,
            100 + (int) $instructionDossier->id
        );
    }

    public function upsertAvis(
        DossierAnalyseCritique $dossier,
        string $sourceType,
        ?string $sourceLabel,
        ?string $contenu,
        string $etat,
        $emisAt,
        ?int $emisParUserId,
        ?int $instructionDossierId,
        int $sortOrder
    ): AnalyseCritiqueAvis {
        $avis = AnalyseCritiqueAvis::query()->firstOrNew([
            'dossier_analyse_critique_id' => $dossier->id,
            'source_type' => $sourceType,
            'source_label' => $sourceLabel,
            'instruction_dossier_id' => $instructionDossierId,
        ]);

        $avis->contenu = $contenu;
        $avis->etat = $etat;
        $avis->emis_at = $emisAt;
        $avis->emis_par_user_id = $emisParUserId;
        $avis->sort_order = $sortOrder;
        $avis->save();

        return $avis;
    }
}
