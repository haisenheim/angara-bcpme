<?php

namespace App\Services\Inertia;

use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\User;
use App\Services\StructurationClosureService;
use App\Support\Inertia\Badge;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Espace chef de filière : bloc « structuration et dossier d'instruction » de la fiche client
 * (page Structuration/Client) et écran de structuration (page Structuration/Show).
 *
 * Reprend les conditions des vues Blade ChefFiliere/clients/show, partials/qualification-section-compact
 * et ChefFiliere/qualifications/show ; les contrôles d'écriture restent dans les contrôleurs.
 */
class StructurationPresenter
{
    public function __construct(private readonly StructurationClosureService $closure)
    {
    }

    /**
     * @param  Collection<int, \App\Models\Programme>  $availableProgrammes  programmes encore libres (bundleAvailableProgrammes)
     */
    public function clientWorkflow(Entreprise $item, User $user, ?Dossier $bundleEditDossier, Collection $availableProgrammes): array
    {
        $eer = $item->dossierEntreeRelation;
        $validated = (bool) $eer?->qualification_validated_by_agence_at;
        $instructionDossiers = $item->dossiers->filter(fn ($d) => $d->instructionProgrammes->isNotEmpty())->sortByDesc('created_at')->values();
        $canCompose = $validated && $availableProgrammes->isNotEmpty();

        return [
            'structuration' => $this->strip($item, $eer, $user),
            'dossiers' => $validated ? $instructionDossiers->map(fn (Dossier $d) => [
                'token' => $d->token,
                'programmes' => $d->programmesLabel(),
                'sollicites' => $this->money($d->engagements_sollicites_total),
                'enCours' => $this->money($d->engagements_en_cours_total),
                'transmisLe' => $this->dt($d->chef_filiere_submitted_to_agence_at),
                'transmisPar' => $d->chefFiliereSubmittedToAgenceBy?->name,
                'statut' => match (true) {
                    $d->isInstructionPendingAgenceValidation() => ['badge' => $this->closure->closureStatutLabel($d), 'variant' => 'warning'],
                    $d->isInstructionValidatedByAgence() => ['badge' => 'Validé', 'variant' => 'success'],
                    $d->isInstructionRejectedByAgence() => ['badge' => 'Rejeté', 'variant' => 'danger'],
                    default => ['badge' => '—', 'variant' => 'neutral'],
                },
                'motif' => $d->isInstructionRejectedByAgence() ? $d->instruction_agence_reject_motif : null,
                'href' => route('chef-filiere.instructions.dossier.show', $d->token),
            ])->values()->all() : [],
            'composer' => $canCompose ? $this->composer($item, $bundleEditDossier, $availableProgrammes) : null,
            'noProgrammeLeft' => $validated && $availableProgrammes->isEmpty(),
        ];
    }

    /**
     * Bandeau de structuration (équivalent de partials/qualification-section-compact, espace chef de filière).
     */
    private function strip(Entreprise $item, ?DossierEntreeRelation $eer, User $user): array
    {
        $showDetail = $eer && (
            $eer->qualification_completed_at || $eer->programmes_submitted_at || $eer->qualification_validated_by_agence_at
            || in_array($eer->statut, [
                DossierEntreeRelation::STATUT_QUALIFIE, DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION,
                DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE, DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE,
            ], true)
        );

        $status = ['badge' => 'Non démarrée', 'variant' => 'neutral', 'detail' => null];
        $at = null;
        if ($eer && $showDetail && $eer->instruction_validated_at && $eer->statut === DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE) {
            $status = ['badge' => 'Instruction validée (CA)', 'variant' => 'success', 'detail' => null];
            $at = ['label' => 'Validation instruction', 'value' => $this->dt($eer->instruction_validated_at)];
        } elseif ($eer && $showDetail) {
            $status = Badge::fromPresentation(DossierEntreeRelation::clientStructurationPresentation($eer));
            $at = match (true) {
                (bool) $eer->qualification_validated_by_agence_at => ['label' => 'Validée le', 'value' => $this->dt($eer->qualification_validated_by_agence_at)],
                (bool) $eer->programmes_submitted_at => ['label' => 'Soumise le', 'value' => $this->dt($eer->programmes_submitted_at)],
                (bool) $eer->qualification_rejected_by_agence_at => ['label' => 'Refus le', 'value' => $this->dt($eer->qualification_rejected_by_agence_at)],
                (bool) $eer->qualification_completed_at => ['label' => 'Dernière sauvegarde', 'value' => $this->dt($eer->qualification_completed_at)],
                default => null,
            };
        } elseif ($eer) {
            $status = Badge::fromPresentation(DossierEntreeRelation::clientStructurationPresentation($eer));
        }

        $isAuthor = $eer && (int) $user->id === (int) $eer->qualification_user_id;
        $completed = (bool) $eer?->qualification_completed_at;

        return [
            'status' => $status,
            'at' => $at,
            'readonlyNotice' => $completed && $isAuthor && $eer->programmes_submitted_at && ! $eer->qualification_validated_by_agence_at
                ? 'Soumise au chef d\'agence — lecture seule.' : null,
            'rejection' => $eer && $eer->qualification_rejected_by_agence_at && $eer->programmes_submitted_at === null && ! $eer->qualification_validated_by_agence_at
                ? ['label' => 'Structuration refusée par le chef d\'agence', 'at' => $this->dt($eer->qualification_rejected_by_agence_at), 'by' => null, 'motif' => $eer->qualification_reject_motif]
                : null,
            'showDetail' => (bool) $showDetail,
            'canStart' => ! $completed,
            'canEdit' => $completed && $isAuthor && ! $eer->programmes_submitted_at,
        ];
    }

    private function composer(Entreprise $item, ?Dossier $edit, Collection $programmes): array
    {
        $lignes = $edit?->instructionProgrammes->map(fn ($l) => [
            'programme_id' => (int) $l->programme_id,
            'budget_appui_financier' => (float) ($l->budget_appui_financier ?? 0),
            'budget_appui_non_financier' => (float) ($l->budget_appui_non_financier ?? 0),
        ])->values()->all() ?? [];

        return [
            'url' => route('chef-filiere.clients.dossier-instruction.submit', $item->token),
            'editDossierId' => $edit?->id ? (int) $edit->id : null,
            'title' => $edit ? 'Correction — dossier d\'instruction (multi-programmes)' : 'Nouveau dossier d\'instruction (multi-programmes)',
            'submitLabel' => $edit ? 'Resoumettre au chef d\'agence' : 'Soumettre le dossier au chef d\'agence',
            'rejection' => $edit ? [
                'label' => 'Dossier rejeté par le chef d\'agence',
                'at' => $this->dt($edit->instruction_agence_rejected_at),
                'by' => $edit->instructionAgenceRejectedBy?->name,
                'motif' => $edit->instruction_agence_reject_motif,
            ] : null,
            'programmes' => $programmes->map(fn ($p) => ['value' => (int) $p->id, 'label' => $p->name])->values()->all(),
            'values' => [
                'engagements_sollicites_total' => $edit?->engagements_sollicites_total !== null ? (float) $edit->engagements_sollicites_total : null,
                'engagements_en_cours_total' => $edit?->engagements_en_cours_total !== null ? (float) $edit->engagements_en_cours_total : null,
                'lignes' => $lignes ?: [['programme_id' => null, 'budget_appui_financier' => 0, 'budget_appui_non_financier' => 0]],
            ],
        ];
    }

    /**
     * Écran de structuration du chef de filière (formulaire ou lecture seule).
     */
    public function qualification(Entreprise $item, DossierEntreeRelation $eer, bool $editable, bool $lockedPendingCa, bool $lockedAfterValidation): array
    {
        $badges = [];
        if ($item->promu_client_at) {
            $badges[] = ['label' => 'Client validé (chef d\'agence)', 'variant' => 'success', 'sub' => trim($this->dt($item->promu_client_at).' — '.($item->promuClientUser?->name ?? '—'))];
        } elseif ($item->prospect_rejected_at) {
            $badges[] = ['label' => 'Refus chef d\'agence', 'variant' => 'warning', 'sub' => trim($this->dt($item->prospect_rejected_at).' — '.($item->prospectRejectedUser?->name ?? '—'))];
        }
        if ($eer->programmes_submitted_at) {
            $badges[] = ['label' => 'Structuration soumise au chef d\'agence', 'variant' => 'info', 'sub' => $this->dt($eer->programmes_submitted_at)];
        }
        if ($eer->qualification_validated_by_agence_at) {
            $badges[] = ['label' => 'Structuration validée (chef d\'agence)', 'variant' => 'success', 'sub' => $this->dt($eer->qualification_validated_by_agence_at)];
        }
        $rejected = $eer->qualification_rejected_by_agence_at && $eer->programmes_submitted_at === null && ! $eer->qualification_validated_by_agence_at;
        if ($rejected) {
            $badges[] = ['label' => 'Structuration refusée par le chef d\'agence', 'variant' => 'danger', 'sub' => $this->dt($eer->qualification_rejected_by_agence_at)];
        }

        $sections = collect([
            'Analyse stratégique' => $eer->analyse_strategique,
            'Analyse opérationnelle' => $eer->analyse_operationnelle,
            'Éligibilité' => $eer->analyse_eligibilite,
            'Identification des besoins' => $eer->identification_besoins,
            'Notes de structuration' => $eer->qualification_notes,
        ])->filter(fn ($html) => trim(strip_tags((string) $html)) !== '')
            ->map(fn ($html, $label) => ['label' => $label, 'html' => $html])->values()->all();

        return [
            'entreprise' => ['name' => $item->name, 'token' => $item->token, 'agence' => $item->agence?->name],
            'badges' => $badges,
            'rejection' => $rejected ? [
                'label' => 'Structuration rejetée par le chef d\'agence',
                'at' => $this->dt($eer->qualification_rejected_by_agence_at),
                'by' => null,
                'motif' => $eer->qualification_reject_motif,
            ] : null,
            'editable' => $editable,
            'lock' => $lockedPendingCa ? 'pending' : ($lockedAfterValidation ? 'validated' : null),
            'values' => [
                'analyse_strategique' => $eer->analyse_strategique ?? '',
                'analyse_operationnelle' => $eer->analyse_operationnelle ?? '',
                'analyse_eligibilite' => $eer->analyse_eligibilite ?? '',
                'identification_besoins' => $eer->identification_besoins ?? '',
                'qualification_notes' => $eer->qualification_notes ?? '',
                'besoin_financement' => (bool) $eer->besoin_financement,
                'besoin_accompagnement' => (bool) $eer->besoin_accompagnement,
                'besoin_structuration' => (bool) $eer->besoin_structuration,
            ],
            'readonly' => [
                'sections' => $sections,
                'besoins' => array_values(array_filter([
                    $eer->besoin_financement ? 'Financement' : null,
                    $eer->besoin_accompagnement ? 'Accompagnement' : null,
                    $eer->besoin_structuration ? 'Structuration' : null,
                ])),
                'programmes' => $eer->programmeSelections->map(fn ($s) => [
                    'name' => $s->programme?->name ?? '—',
                    'type' => $s->type_appui,
                    'statut' => $s->statut,
                ])->values()->all(),
            ],
            // Soumission proposée seulement depuis le formulaire, une fois la structuration enregistrée (comme le Blade).
            'canSubmit' => $editable && $eer->qualification_completed_at && ! $eer->programmes_submitted_at,
            'urls' => [
                'update' => route('chef-filiere.qualifications.update', $item->token),
                'submit' => route('chef-filiere.qualifications.submit', $item->token),
                'client' => route('chef-filiere.clients.show', $item->token),
            ],
        ];
    }

    private function money($value): ?string
    {
        return ($value !== null && $value !== '') ? number_format((float) $value, 0, ',', ' ').' FCFA' : null;
    }

    private function dt($value): ?string
    {
        return $value ? Carbon::parse($value)->format('d/m/Y H:i') : null;
    }
}
