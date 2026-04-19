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

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'qualification_completed_at' => 'datetime',
            'programmes_submitted_at' => 'datetime',
            'instruction_validated_at' => 'datetime',
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

    public function programmeSelections(): HasMany
    {
        return $this->hasMany(DossierEntreeRelationProgramme::class)->orderBy('id');
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
}
