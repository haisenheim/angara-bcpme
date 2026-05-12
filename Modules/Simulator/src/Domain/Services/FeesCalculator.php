<?php

namespace Modules\Simulator\Domain\Services;

use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\InsuranceBasis;

/**
 * Pure-PHP helpers for fee/insurance/VAT computations.
 * Used by AmortizationCalculator and exposed for unit testing in isolation.
 */
final class FeesCalculator
{
    public function dossierFees(SimulationInput $input): float
    {
        return $input->dossierFeeFixed + ($input->principal * $input->dossierFeePct / 100.0);
    }

    public function insurancePerPeriod(SimulationInput $input, float $capitalAtPeriodStart): float
    {
        if ($input->insurancePct <= 0) {
            return 0.0;
        }

        $base = $input->insuranceBasis === InsuranceBasis::INITIAL_PRINCIPAL
            ? $input->principal
            : $capitalAtPeriodStart;

        return $base * ($input->insurancePct / 100.0) / $input->periodicity->periodsPerYear();
    }

    public function vat(SimulationInput $input, float $taxableBase): float
    {
        if ($input->vatRate <= 0) {
            return 0.0;
        }

        return $taxableBase * $input->vatRate / 100.0;
    }
}
