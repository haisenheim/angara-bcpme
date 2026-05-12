<?php

namespace Modules\Simulator\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;

class SimulateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $currencies = array_keys((array) config('simulator.currencies', []));

        return [
            'principal' => ['required', 'numeric', 'gt:0'],
            'annual_rate' => ['required', 'numeric', 'gte:0'],
            'term_periods' => ['required', 'integer', 'min:1', 'max:600'],
            'periodicity' => ['required', 'string', 'in:'.implode(',', array_column(Periodicity::cases(), 'value'))],
            'amortization_type' => ['required', 'string', 'in:'.implode(',', array_column(AmortizationType::cases(), 'value'))],
            'deferral_type' => ['nullable', 'string', 'in:'.implode(',', array_column(DeferralType::cases(), 'value'))],
            'deferral_periods' => ['nullable', 'integer', 'min:0'],
            'first_period_date' => ['nullable', 'date'],
            'currency' => ['required', 'string', 'size:3', 'in:'.implode(',', $currencies)],
            'fx_rate' => ['nullable', 'numeric', 'gt:0'],
            'dossier_fee_fixed' => ['nullable', 'numeric', 'gte:0'],
            'dossier_fee_pct' => ['nullable', 'numeric', 'gte:0', 'max:100'],
            'insurance_pct' => ['nullable', 'numeric', 'gte:0', 'max:100'],
            'insurance_basis' => ['nullable', 'string', 'in:'.implode(',', array_column(InsuranceBasis::cases(), 'value'))],
            'vat_rate' => ['nullable', 'numeric', 'gte:0', 'max:100'],
        ];
    }

    public function toInput(): SimulationInput
    {
        return SimulationInput::fromArray($this->validated());
    }
}
