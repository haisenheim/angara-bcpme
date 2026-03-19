<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DossierEsgEvaluation extends Model
{
    use HasFactory;

    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_VALIDATED = 'validated';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'dossier_id', 'entreprise_id', 'programme_id', 'agence_id', 'gestionnaire_id', 'analyste_id',
        'reference_framework', 'evaluation_type', 'status',
        'evaluation_date', 'submitted_at', 'validated_at', 'rejected_at',
        'score_environmental', 'score_social', 'score_governance', 'score_financial', 'score_compliance', 'score_global',
        'risk_level', 'bankability_level',
        'eligibility_blending', 'eligibility_guarantee', 'eligibility_global_gateway',
        'exclusion_flag', 'minimum_compliance_passed', 'sdg_alignment',
        'strengths', 'weaknesses', 'recommendations', 'due_diligence_notes',
        'analyst_conclusion', 'validation_comment', 'rejection_reason',
        'created_by', 'updated_by', 'submitted_by', 'validated_by', 'rejected_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'rejected_at' => 'datetime',
        'evaluation_date' => 'date',
        'score_environmental' => 'decimal:2',
        'score_social' => 'decimal:2',
        'score_governance' => 'decimal:2',
        'score_financial' => 'decimal:2',
        'score_compliance' => 'decimal:2',
        'score_global' => 'decimal:2',
        'eligibility_blending' => 'boolean',
        'eligibility_guarantee' => 'boolean',
        'eligibility_global_gateway' => 'boolean',
        'exclusion_flag' => 'boolean',
        'minimum_compliance_passed' => 'boolean',
        'sdg_alignment' => 'array',
    ];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function analyste()
    {
        return $this->belongsTo(User::class, 'analyste_id');
    }

    public function items()
    {
        return $this->hasMany(DossierEsgEvaluationItem::class, 'dossier_esg_evaluation_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === self::STATUS_SUBMITTED;
    }

    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDATED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function canBeEdited(): bool
    {
        return $this->isDraft() || $this->isRejected();
    }
}
