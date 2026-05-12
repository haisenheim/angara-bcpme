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
class AmortizationCalculatorInFineTest extends TestCase
{
    public function test_in_fine_capital_is_paid_only_at_last_period(): void
    {
        $input = new SimulationInput(
            principal: 5_000_000.0,
            annualRate: 12.0,
            termPeriods: 5,
            periodicity: Periodicity::ANNUAL,
            amortizationType: AmortizationType::IN_FINE,
        );

        $result = (new AmortizationCalculator)->compute($input);

        // 4 first periods: only interest, principal paid = 0
        for ($i = 0; $i < 4; $i++) {
            $this->assertEqualsWithDelta(0.0, $result->lines[$i]->principalPaid, 0.01);
            $this->assertEqualsWithDelta(600_000.0, $result->lines[$i]->interestPaid, 0.01); // 5M * 12%
        }
        // last period: principal in full
        $this->assertEqualsWithDelta(5_000_000.0, $result->lines[4]->principalPaid, 0.01);
        $this->assertEqualsWithDelta(0.0, $result->lines[4]->capitalDueEnd, 0.01);
    }
}
