<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Dossier d’entrée en relation (EER), un par entreprise.
 * Réponses formulaire EER : {@see QuestionAnswer} / questions_answers liées à l’entreprise.
 * Tiers : {@see Tier} sur entreprise_id.
 * Pièces exigibles : {@see EntreprisePieceExigible}.
 */
class DossierEntreeRelation extends Model
{
    use HasFactory;

    public const STATUT_BROUILLON = 'brouillon';

    public const STATUT_SOUMIS = 'soumis';

    public const STATUT_CLIENT_VALIDE = 'client_valide';

    public const STATUT_QUALIFIE = 'qualifie';

    public const STATUT_EN_VALIDATION_INSTRUCTION = 'en_validation_instruction';

    public const STATUT_INSTRUCTION_VALIDEE = 'instruction_validee';

    /** Structuration validée par le chef d’agence (valeur historique : qualification_validee_agence) — inscription programme possible. */
    public const STATUT_QUALIFICATION_AGENCE_VALIDEE = 'qualification_validee_agence';

    /** Structuration (EER) refusée par le chef d’agence — le chef de filière doit corriger et resoumettre. */
    public const STATUT_QUALIFICATION_AGENCE_REJETEE = 'qualification_rejetee_agence';

    /** Statut métier « client » : structuration validée par l’agence. */
    public const CLIENT_STRUCT_STATUS_STRUCTURE = 'structure';

    /** Structuration en cours (chez le chef de filière ou transmise, en attente de validation agence). */
    public const CLIENT_STRUCT_STATUS_EN_COURS = 'structuration_en_cours';

    /** Pas encore de structuration complétée côté chef de filière. */
    public const CLIENT_STRUCT_STATUS_ATTENTE = 'en_attente_structuration';

    /** Dernière soumission refusée par le chef d’agence (tant que non resoumise). */
    public const CLIENT_STRUCT_STATUS_REJETEE = 'structuration_rejetee';

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'qualification_completed_at' => 'datetime',
            'programmes_submitted_at' => 'datetime',
            'instruction_validated_at' => 'datetime',
            'qualification_validated_by_agence_at' => 'datetime',
            'qualification_rejected_by_agence_at' => 'datetime',
            'instruction_bundle_submitted_at' => 'datetime',
            'instruction_bundle_validated_at' => 'datetime',
            'instruction_bundle_rejected_at' => 'datetime',
            'besoin_financement' => 'boolean',
            'besoin_accompagnement' => 'boolean',
            'besoin_structuration' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function entreprise(): BelongsTo
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_user_id');
    }

    public function qualificationUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qualification_user_id');
    }

    public function programmesSubmittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'programmes_submitted_by_user_id');
    }

    public function instructionValidatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruction_validated_by_user_id');
    }

    public function qualificationValidatedByAgenceUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qualification_validated_by_agence_user_id');
    }

    public function qualificationRejectedByAgenceUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qualification_rejected_by_agence_user_id');
    }

    public function programmeSelections(): HasMany
    {
        return $this->hasMany(DossierEntreeRelationProgramme::class)->orderBy('id');
    }

    public function instructionBundleDossier(): BelongsTo
    {
        return $this->belongsTo(Dossier::class, 'instruction_bundle_dossier_id');
    }

    public function instructionBundleSubmittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruction_bundle_submitted_by_user_id');
    }

    public function instructionBundleValidatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruction_bundle_validated_by_user_id');
    }

    public function instructionBundleRejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instruction_bundle_rejected_by_user_id');
    }

    /** Au moins un dossier d’instruction (multi-programmes) est en attente de validation agence. */
    public function isInstructionBundlePendingChefAgence(): bool
    {
        return Dossier::on('central_app_mysql')
            ->where('entreprise_id', $this->entreprise_id)
            ->whereNotNull('chef_filiere_submitted_to_agence_at')
            ->whereNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at')
            ->whereHas('instructionProgrammes')
            ->exists();
    }

    /** Au moins un dossier d’instruction a été rejeté par l’agence et non validé depuis. */
    public function isInstructionBundleRejectedByChefAgence(): bool
    {
        return Dossier::on('central_app_mysql')
            ->where('entreprise_id', $this->entreprise_id)
            ->whereNotNull('instruction_agence_rejected_at')
            ->whereNull('instruction_agence_validated_at')
            ->whereHas('instructionProgrammes')
            ->exists();
    }

    public function tiers()
    {
        return $this->entreprise?->tiers();
    }

    public function reponses()
    {
        return $this->entreprise?->reponses();
    }

    public function piecesExigibles()
    {
        return $this->entreprise?->piecesExigibles();
    }

    /**
     * Libellé métier du statut EER (affichage UI) — ne modifie pas la valeur stockée en base.
     */
    public static function statutLibelle(?string $statut): string
    {
        return match ($statut) {
            self::STATUT_BROUILLON => 'Brouillon (EER)',
            self::STATUT_SOUMIS => 'Soumis (EER)',
            self::STATUT_CLIENT_VALIDE => 'Client validé (EER)',
            self::STATUT_QUALIFIE => 'Structuration enregistrée',
            self::STATUT_EN_VALIDATION_INSTRUCTION => 'Structuration soumise — en validation agence',
            self::STATUT_QUALIFICATION_AGENCE_VALIDEE => 'Structuration validée par l’agence',
            self::STATUT_QUALIFICATION_AGENCE_REJETEE => 'Structuration refusée par l’agence',
            self::STATUT_INSTRUCTION_VALIDEE => 'Instruction validée (agence)',
            null, '' => 'Non initialisé',
            default => 'Statut : '.$statut,
        };
    }

    public function getStatutLibelleAttribute(): string
    {
        return self::statutLibelle($this->statut);
    }

    /**
     * Code de statut métier « structuration client » (prompt métier BC-PME).
     */
    public function clientStructurationStatusCode(): string
    {
        if ($this->qualification_validated_by_agence_at) {
            return self::CLIENT_STRUCT_STATUS_STRUCTURE;
        }
        if ($this->qualification_rejected_by_agence_at && $this->programmes_submitted_at === null) {
            return self::CLIENT_STRUCT_STATUS_REJETEE;
        }
        if ($this->programmes_submitted_at) {
            return self::CLIENT_STRUCT_STATUS_EN_COURS;
        }
        if ($this->qualification_completed_at) {
            return self::CLIENT_STRUCT_STATUS_EN_COURS;
        }

        return self::CLIENT_STRUCT_STATUS_ATTENTE;
    }

    public function isClientStructurationStructure(): bool
    {
        return $this->clientStructurationStatusCode() === self::CLIENT_STRUCT_STATUS_STRUCTURE;
    }

    /** Cas 2, 3 et 4 : tout sauf « structuré » (validé agence). */
    public function isClientStructurationNonStructure(): bool
    {
        return ! $this->isClientStructurationStructure();
    }

    /**
     * @return array{code: string, label: string, badge_variant: string, detail: ?string}
     */
    public static function clientStructurationPresentation(?self $eer): array
    {
        if (! $eer) {
            return [
                'code' => self::CLIENT_STRUCT_STATUS_ATTENTE,
                'label' => 'En attente de structuration',
                'badge_variant' => 'secondary',
                'detail' => null,
            ];
        }

        $code = $eer->clientStructurationStatusCode();

        return match ($code) {
            self::CLIENT_STRUCT_STATUS_STRUCTURE => [
                'code' => $code,
                'label' => 'Structuré',
                'badge_variant' => 'success',
                'detail' => null,
            ],
            self::CLIENT_STRUCT_STATUS_EN_COURS => [
                'code' => $code,
                'label' => 'Structuration en cours',
                'badge_variant' => $eer->programmes_submitted_at ? 'warning' : 'info',
                'detail' => $eer->programmes_submitted_at
                    ? 'Transmis au chef d’agence — en attente de validation'
                    : 'Saisie ou enregistrement côté chef de filière — pas encore transmis',
            ],
            self::CLIENT_STRUCT_STATUS_REJETEE => [
                'code' => $code,
                'label' => 'Structuration rejetée',
                'badge_variant' => 'danger',
                'detail' => $eer->qualification_reject_motif
                    ? 'Motif : '.(mb_strlen((string) $eer->qualification_reject_motif) > 120
                        ? mb_substr((string) $eer->qualification_reject_motif, 0, 117).'…'
                        : (string) $eer->qualification_reject_motif)
                    : null,
            ],
            self::CLIENT_STRUCT_STATUS_ATTENTE => [
                'code' => $code,
                'label' => 'En attente de structuration',
                'badge_variant' => 'secondary',
                'detail' => 'Pas encore de structuration complétée par le chef de filière',
            ],
            default => [
                'code' => self::CLIENT_STRUCT_STATUS_ATTENTE,
                'label' => 'En attente de structuration',
                'badge_variant' => 'secondary',
                'detail' => 'Pas encore de structuration complétée par le chef de filière',
            ],
        };
    }

    /** @return array<string, string> code => libellé (filtres listes entreprises / clients) */
    public static function clientStructurationStatusFilterLabels(): array
    {
        return [
            self::CLIENT_STRUCT_STATUS_STRUCTURE => 'Structuré',
            self::CLIENT_STRUCT_STATUS_EN_COURS => 'Structuration en cours',
            self::CLIENT_STRUCT_STATUS_ATTENTE => 'En attente de structuration',
            self::CLIENT_STRUCT_STATUS_REJETEE => 'Structuration rejetée',
            'non_structure' => 'Non structuré (hors validé agence)',
        ];
    }

    /** @return list<string> */
    public static function allowedClientStructurationFilterCodes(): array
    {
        return [
            self::CLIENT_STRUCT_STATUS_STRUCTURE,
            self::CLIENT_STRUCT_STATUS_EN_COURS,
            self::CLIENT_STRUCT_STATUS_ATTENTE,
            self::CLIENT_STRUCT_STATUS_REJETEE,
            'non_structure',
        ];
    }

    public static function normalizeClientStructurationFilter(?string $value): ?string
    {
        $v = trim((string) $value);
        if ($v === '') {
            return null;
        }

        return in_array($v, self::allowedClientStructurationFilterCodes(), true) ? $v : null;
    }
}
