<?php

namespace App\Services;

use App\Models\Dossier;

/**
 * Statut unifié du dossier d'instruction (référence : prompt.txt l.23-27).
 *
 * États possibles :
 *   - validé           : clôture validée (accord) du dossier
 *   - rejeté           : clôture rejetée OU rejet définitif en amont (CA / engagements REXP)
 *   - en_cours         : ni l'un ni l'autre
 *   - valide_et_rejete : parcours mixte (au moins une validation et au moins un rejet intermédiaire au cours du cycle de vie du dossier)
 *
 * NB : la mention « a+b » du prompt est interprétée comme un dossier au parcours mouvementé
 * (rejet(s) intermédiaire(s) suivi(s) ou non d'une validation, ou inversement). Ce code permet
 * de distinguer un dossier « propre » d'un dossier qui a connu des allers-retours.
 */
class DossierInstructionStatutService
{
    public const CODE_VALIDE = 'valide';

    public const CODE_REJETE = 'rejete';

    public const CODE_EN_COURS = 'en_cours';

    public const CODE_VALIDE_ET_REJETE = 'valide_et_rejete';

    /**
     * @return list<string>
     */
    public static function allCodes(): array
    {
        return [self::CODE_VALIDE, self::CODE_REJETE, self::CODE_EN_COURS, self::CODE_VALIDE_ET_REJETE];
    }

    /**
     * Détermine le code de statut du dossier d'instruction.
     */
    public function statusCode(Dossier $dossier): string
    {
        $hasValide = $this->hasValidationEvent($dossier);
        $hasRejet = $this->hasRejectionEvent($dossier);
        $finalValide = $dossier->instruction_closure_validated_at !== null;
        $finalRejete = $dossier->instruction_closure_rejected_at !== null
            || ($dossier->instruction_agence_rejected_at !== null && $dossier->instruction_agence_validated_at === null)
            || ($dossier->exploitation_engagements_decision === 'rejet' && $dossier->instruction_closure_validated_at === null);

        if ($finalValide && $hasRejet) {
            return self::CODE_VALIDE_ET_REJETE;
        }
        if ($finalValide) {
            return self::CODE_VALIDE;
        }
        if ($finalRejete) {
            return self::CODE_REJETE;
        }
        if ($hasValide && $hasRejet) {
            return self::CODE_VALIDE_ET_REJETE;
        }

        return self::CODE_EN_COURS;
    }

    /**
     * Présentation pour l'UI (label, variante de badge, détail).
     *
     * @return array{code: string, label: string, badge_variant: string, detail: ?string}
     */
    public function presentation(Dossier $dossier): array
    {
        $code = $this->statusCode($dossier);

        return match ($code) {
            self::CODE_VALIDE => [
                'code' => $code,
                'label' => 'Dossier validé',
                'badge_variant' => 'success',
                'detail' => $dossier->instruction_closure_validated_at?->format('d/m/Y H:i'),
            ],
            self::CODE_REJETE => [
                'code' => $code,
                'label' => 'Dossier rejeté',
                'badge_variant' => 'danger',
                'detail' => $this->describeRejectionReason($dossier),
            ],
            self::CODE_VALIDE_ET_REJETE => [
                'code' => $code,
                'label' => 'Dossier validé et rejeté (parcours mixte)',
                'badge_variant' => 'warning',
                'detail' => 'Au moins un rejet et une validation au cours du cycle d’instruction.',
            ],
            self::CODE_EN_COURS => [
                'code' => self::CODE_EN_COURS,
                'label' => 'Instruction en cours',
                'badge_variant' => 'info',
                'detail' => $this->describeCurrentStep($dossier),
            ],
            default => [
                'code' => self::CODE_EN_COURS,
                'label' => 'Instruction en cours',
                'badge_variant' => 'info',
                'detail' => $this->describeCurrentStep($dossier),
            ],
        };
    }

    /**
     * Libellés pour les filtres / listes.
     *
     * @return array<string, string>
     */
    public static function filterLabels(): array
    {
        return [
            self::CODE_VALIDE => 'Dossier validé',
            self::CODE_REJETE => 'Dossier rejeté',
            self::CODE_EN_COURS => 'Instruction en cours',
            self::CODE_VALIDE_ET_REJETE => 'Validé et rejeté (mixte)',
        ];
    }

    /**
     * Détecte la présence d'au moins un événement de validation au cours du cycle de vie.
     */
    private function hasValidationEvent(Dossier $dossier): bool
    {
        return $dossier->instruction_agence_validated_at !== null
            || $dossier->exploitation_engagements_decision === 'accord'
            || $dossier->instruction_closure_validated_at !== null;
    }

    /**
     * Détecte la présence d'au moins un événement de rejet (intermédiaire ou final) au cours du cycle de vie.
     */
    private function hasRejectionEvent(Dossier $dossier): bool
    {
        return $dossier->instruction_agence_rejected_at !== null
            || $dossier->exploitation_analyste_rejected_at !== null
            || $dossier->juridique_analyste_rejected_at !== null
            || $dossier->reng_analyste_credit_rejected_at !== null
            || $dossier->rerx_analyste_risques_rejected_at !== null
            || $dossier->exploitation_engagements_decision === 'rejet'
            || $dossier->instruction_closure_rejected_at !== null;
    }

    private function describeRejectionReason(Dossier $dossier): ?string
    {
        if ($dossier->instruction_closure_rejected_at !== null) {
            return 'Rejet de clôture le '.$dossier->instruction_closure_rejected_at->format('d/m/Y H:i');
        }
        if ($dossier->instruction_agence_rejected_at !== null && $dossier->instruction_agence_validated_at === null) {
            return 'Rejet par le chef d’agence le '.$dossier->instruction_agence_rejected_at->format('d/m/Y H:i');
        }
        if ($dossier->exploitation_engagements_decision === 'rejet') {
            return 'Rejet du dossier des engagements (responsable exploitation)';
        }

        return null;
    }

    private function describeCurrentStep(Dossier $dossier): ?string
    {
        if ($dossier->isInstructionPendingAgenceValidation()) {
            return 'En attente de validation par le chef d’agence';
        }
        if ($dossier->rerx_submitted_to_direction_at !== null) {
            return 'Chez la direction (DG / DGA / délégation de pouvoir)';
        }
        if ($dossier->reng_submitted_to_risques_at !== null) {
            return 'Au pôle risques';
        }
        if ($dossier->juridique_submitted_to_engagements_at !== null) {
            return 'Au pôle engagements';
        }
        if ($dossier->juridique_instruction_submitted_at !== null) {
            return 'Au pôle juridique';
        }
        if ($dossier->isInstructionVisibleToResponsableExploitation()) {
            return 'Au pôle exploitation';
        }

        return null;
    }
}
