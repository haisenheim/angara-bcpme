<?php

namespace Modules\Simulator\Tests\Unit;

use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Domain\Services\AmortizationCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @group simulator
 */
class AmortizationCalculatorConstantTest extends TestCase
{
    public function test_constant_payment_balance_reaches_zero(): void
    {
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 60,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );

        $result = (new AmortizationCalculator)->compute($input);

        $this->assertCount(60, $result->lines);
        $last = $result->lines[array_key_last($result->lines)];
        $this->assertSame(0.0, $last->capitalDueEnd);
        $this->assertEqualsWithDelta(10_000_000.0, $result->totalPrincipal, 0.5);
    }

    public function test_constant_payment_value_matches_known_formula(): void
    {
        // Excel PMT(8%/12, 60, -10_000_000) = 202 764,12 monthly payment.
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 60,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );

        $result = (new AmortizationCalculator)->compute($input);

        $first = $result->lines[0];
        $this->assertEqualsWithDelta(202_764.12, $first->totalPayment, 1.0);
    }

    public function test_zero_rate_constant_loan_pays_only_principal(): void
    {
        $input = new SimulationInput(
            principal: 1_200_000.0,
            annualRate: 0.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );

        $result = (new AmortizationCalculator)->compute($input);

        $this->assertEqualsWithDelta(0.0, $result->totalInterest, 0.01);
        $this->assertEqualsWithDelta(100_000.0, $result->lines[0]->principalPaid, 0.01);
        $last = $result->lines[array_key_last($result->lines)];
        $this->assertEqualsWithDelta(0.0, $last->capitalDueEnd, 0.01);
    }
}
