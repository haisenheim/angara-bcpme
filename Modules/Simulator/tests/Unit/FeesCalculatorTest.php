<?php

namespace Modules\Simulator\Tests\Unit;

use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Domain\Services\FeesCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @group simulator
 */
class FeesCalculatorTest extends TestCase
{
    public function test_dossier_fees_combine_fixed_and_percent(): void
    {
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            dossierFeeFixed: 50_000.0,
            dossierFeePct: 1.0,
        );

        $fees = (new FeesCalculator)->dossierFees($input);

        $this->assertEqualsWithDelta(150_000.0, $fees, 0.01);
    }

    public function test_insurance_initial_principal_is_constant(): void
    {
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            insurancePct: 0.6,
            insuranceBasis: InsuranceBasis::INITIAL_PRINCIPAL,
        );

        $calculator = new FeesCalculator;
        $period1 = $calculator->insurancePerPeriod($input, 10_000_000.0);
        $period6 = $calculator->insurancePerPeriod($input, 5_000_000.0);

        $this->assertEqualsWithDelta(5_000.0, $period1, 0.01);
        $this->assertSame($period1, $period6);
    }

    public function test_insurance_outstanding_balance_decreases(): void
    {
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            insurancePct: 0.6,
            insuranceBasis: InsuranceBasis::OUTSTANDING_BALANCE,
        );

        $calculator = new FeesCalculator;
        $first = $calculator->insurancePerPeriod($input, 10_000_000.0);
        $later = $calculator->insurancePerPeriod($input, 5_000_000.0);

        $this->assertGreaterThan($later, $first);
        $this->assertEqualsWithDelta(2_500.0, $later, 0.01);
    }

    public function test_vat_zero_when_rate_is_zero(): void
    {
        $input = new SimulationInput(
            principal: 1_000_000.0,
            annualRate: 5.0,
            termPeriods: 6,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );

        $this->assertSame(0.0, (new FeesCalculator)->vat($input, 100_000.0));
    }
}
