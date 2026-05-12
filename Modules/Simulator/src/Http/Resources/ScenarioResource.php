<?php

namespace Modules\Simulator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Simulator\Persistence\Models\Scenario */
class ScenarioResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token' => $this->token,
            'name' => $this->name,
            'dossier_id' => $this->dossier_id,
            'dossier_instruction_programme_id' => $this->dossier_instruction_programme_id,
            'currency' => $this->currency,
            'fx_rate' => $this->fx_rate !== null ? (float) $this->fx_rate : null,
            'parameters' => [
                'principal' => (float) $this->principal,
                'annual_rate' => (float) $this->annual_rate,
                'term_periods' => (int) $this->term_periods,
                'periodicity' => $this->periodicity,
                'amortization_type' => $this->amortization_type,
                'deferral_type' => $this->deferral_type,
                'deferral_periods' => (int) $this->deferral_periods,
                'first_period_date' => $this->first_period_date?->format('Y-m-d'),
                'dossier_fee_fixed' => (float) $this->dossier_fee_fixed,
                'dossier_fee_pct' => (float) $this->dossier_fee_pct,
                'insurance_pct' => (float) $this->insurance_pct,
                'insurance_basis' => $this->insurance_basis,
                'vat_rate' => (float) $this->vat_rate,
            ],
            'totals' => [
                'computed_teg' => $this->computed_teg !== null ? (float) $this->computed_teg : null,
                'total_principal' => (float) $this->total_principal,
                'total_interest' => (float) $this->total_interest,
                'total_fees' => (float) $this->total_fees,
                'total_insurance' => (float) $this->total_insurance,
                'total_vat' => (float) $this->total_vat,
                'total_due' => (float) $this->total_due,
                'first_payment' => (float) $this->first_payment,
                'max_payment' => (float) $this->max_payment,
            ],
            'workflow' => [
                'status' => $this->status,
                'is_locked' => (bool) $this->is_locked,
                'submitted_at' => $this->submitted_at?->toIso8601String(),
                'validated_at' => $this->validated_at?->toIso8601String(),
                'rejected_at' => $this->rejected_at?->toIso8601String(),
                'rejection_reason' => $this->rejection_reason,
            ],
            'lines' => ScheduleLineResource::collection($this->whenLoaded('lines')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
