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
class AmortizationCalculatorLinearTest extends TestCase
{
    public function test_linear_amortization_uses_constant_principal(): void
    {
        $input = new SimulationInput(
            principal: 12_000_000.0,
            annualRate: 10.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::LINEAR,
        );

        $result = (new AmortizationCalculator)->compute($input);

        foreach ($result->lines as $line) {
            $this->assertEqualsWithDelta(1_000_000.0, $line->principalPaid, 0.5);
        }
        $last = $result->lines[array_key_last($result->lines)];
        $this->assertEqualsWithDelta(0.0, $last->capitalDueEnd, 0.01);
    }

    public function test_linear_quarterly_total_principal_matches(): void
    {
        $input = new SimulationInput(
            principal: 8_000_000.0,
            annualRate: 6.0,
            termPeriods: 8,
            periodicity: Periodicity::QUARTERLY,
            amortizationType: AmortizationType::LINEAR,
        );

        $result = (new AmortizationCalculator)->compute($input);

        $this->assertCount(8, $result->lines);
        $this->assertEqualsWithDelta(8_000_000.0, $result->totalPrincipal, 0.5);
        $this->assertGreaterThan($result->lines[0]->totalPayment * 0.5, $result->lines[0]->totalPayment);
        // Linear payments are decreasing because interest decreases with capital.
        $this->assertGreaterThan($result->lines[7]->totalPayment, $result->lines[0]->totalPayment);
    }
}
