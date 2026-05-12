<?php

namespace Modules\Simulator\Tests\Unit;

use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Domain\Services\AmortizationCalculator;
use PHPUnit\Framework\TestCase;

/**
 * @group simulator
 */
class AmortizationCalculatorDeferralTest extends TestCase
{
    public function test_partial_deferral_pays_interest_only(): void
    {
        $input = new SimulationInput(
            principal: 6_000_000.0,
            annualRate: 12.0,
            termPeriods: 24,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            deferralType: DeferralType::PARTIAL,
            deferralPeriods: 6,
        );

        $result = (new AmortizationCalculator)->compute($input);

        for ($i = 0; $i < 6; $i++) {
            $this->assertTrue($result->lines[$i]->isDeferred);
            $this->assertEqualsWithDelta(0.0, $result->lines[$i]->principalPaid, 0.01);
            // Interest = 6_000_000 * 1% = 60_000 each deferral month
            $this->assertEqualsWithDelta(60_000.0, $result->lines[$i]->interestPaid, 0.01);
        }
        $this->assertFalse($result->lines[6]->isDeferred);
        $last = $result->lines[array_key_last($result->lines)];
        $this->assertEqualsWithDelta(0.0, $last->capitalDueEnd, 0.01);
    }

    public function test_total_deferral_capitalises_interest(): void
    {
        $input = new SimulationInput(
            principal: 1_000_000.0,
            annualRate: 12.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            deferralType: DeferralType::TOTAL,
            deferralPeriods: 3,
        );

        $result = (new AmortizationCalculator)->compute($input);

        for ($i = 0; $i < 3; $i++) {
            $this->assertTrue($result->lines[$i]->isDeferred);
            $this->assertEqualsWithDelta(0.0, $result->lines[$i]->totalPayment, 0.01);
        }
        // Capital after 3 months of capitalisation at 1% = 1M * 1.01^3 ~= 1_030_301
        $this->assertEqualsWithDelta(1_030_301.0, $result->lines[3]->capitalDueStart, 1.0);
        $last = $result->lines[array_key_last($result->lines)];
        $this->assertEqualsWithDelta(0.0, $last->capitalDueEnd, 0.01);
    }
}
