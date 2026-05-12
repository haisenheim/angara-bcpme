<?php

namespace Modules\Simulator\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \Modules\Simulator\Persistence\Models\ScheduleLine */
class ScheduleLineResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'period_index' => (int) $this->period_index,
            'period_date' => $this->period_date?->format('Y-m-d'),
            'capital_due_start' => (float) $this->capital_due_start,
            'principal_paid' => (float) $this->principal_paid,
            'interest_paid' => (float) $this->interest_paid,
            'insurance_paid' => (float) $this->insurance_paid,
            'fees_paid' => (float) $this->fees_paid,
            'vat_paid' => (float) $this->vat_paid,
            'total_payment' => (float) $this->total_payment,
            'capital_due_end' => (float) $this->capital_due_end,
            'is_deferred' => (bool) $this->is_deferred,
        ];
    }
}
