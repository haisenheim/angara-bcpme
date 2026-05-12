<?php

namespace Modules\Simulator\Persistence\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Domain\Enums\ScenarioStatus;
use RuntimeException;

class Scenario extends Model
{
    use SoftDeletes;

    protected $table = 'simulator_scenarios';

    protected $guarded = [];

    protected $casts = [
        'principal' => 'decimal:2',
        'annual_rate' => 'decimal:4',
        'term_periods' => 'integer',
        'deferral_periods' => 'integer',
        'first_period_date' => 'date',
        'fx_rate' => 'decimal:8',
        'dossier_fee_fixed' => 'decimal:2',
        'dossier_fee_pct' => 'decimal:4',
        'insurance_pct' => 'decimal:4',
        'vat_rate' => 'decimal:4',
        'computed_teg' => 'decimal:4',
        'total_principal' => 'decimal:2',
        'total_interest' => 'decimal:2',
        'total_fees' => 'decimal:2',
        'total_insurance' => 'decimal:2',
        'total_vat' => 'decimal:2',
        'total_due' => 'decimal:2',
        'first_payment' => 'decimal:2',
        'max_payment' => 'decimal:2',
        'is_locked' => 'boolean',
        'submitted_at' => 'datetime',
        'validated_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function getConnectionName(): string
    {
        return config('simulator.connection', 'central_app_mysql');
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    protected static function booted(): void
    {
        static::creating(function (self $scenario): void {
            if (empty($scenario->token)) {
                $scenario->token = (string) Str::uuid();
            }
        });

        static::updating(function (self $scenario): void {
            if (! $scenario->is_locked) {
                return;
            }

            $protected = [
                'principal',
                'annual_rate',
                'term_periods',
                'periodicity',
                'amortization_type',
                'deferral_type',
                'deferral_periods',
                'first_period_date',
                'currency',
                'fx_rate',
                'dossier_fee_fixed',
                'dossier_fee_pct',
                'insurance_pct',
                'insurance_basis',
                'vat_rate',
            ];

            foreach ($protected as $field) {
                if ($scenario->isDirty($field)) {
                    throw new RuntimeException(sprintf(
                        'Le scenario %s est verrouille (statut %s) : modification du champ "%s" interdite.',
                        $scenario->token,
                        $scenario->status,
                        $field,
                    ));
                }
            }
        });
    }

    public function lines(): HasMany
    {
        return $this->hasMany(ScheduleLine::class, 'scenario_id')->orderBy('period_index');
    }

    public function periodicityEnum(): Periodicity
    {
        return Periodicity::from((string) $this->periodicity);
    }

    public function amortizationTypeEnum(): AmortizationType
    {
        return AmortizationType::from((string) $this->amortization_type);
    }

    public function deferralTypeEnum(): DeferralType
    {
        return DeferralType::from((string) $this->deferral_type);
    }

    public function insuranceBasisEnum(): InsuranceBasis
    {
        return InsuranceBasis::from((string) $this->insurance_basis);
    }

    public function statusEnum(): ScenarioStatus
    {
        return ScenarioStatus::from((string) $this->status);
    }

    protected function isAttachedToDossier(): Attribute
    {
        return Attribute::get(fn () => $this->dossier_id !== null);
    }

    protected function isAttachedToProgramme(): Attribute
    {
        return Attribute::get(fn () => $this->dossier_instruction_programme_id !== null);
    }

    public function scopeForUser($query, ?int $userId)
    {
        if ($userId === null) {
            return $query;
        }

        return $query->where('created_by_user_id', $userId);
    }

    public function scopeForDossier($query, int $dossierId)
    {
        return $query->where('dossier_id', $dossierId)
            ->orWhereHas('programmeLine', fn ($q) => $q->where('dossier_id', $dossierId));
    }

    public function programmeLine()
    {
        return $this->belongsTo(
            \App\Models\DossierInstructionProgramme::class,
            'dossier_instruction_programme_id',
        );
    }

    public function dossier()
    {
        return $this->belongsTo(\App\Models\Dossier::class, 'dossier_id');
    }
}
