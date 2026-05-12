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
class TegCalculatorTest extends TestCase
{
    public function test_teg_with_no_fees_equals_nominal_rate(): void
    {
        $input = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 60,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );

        $result = (new AmortizationCalculator)->compute($input);

        // TEG (annual effective) of a nominal 8% monthly compounding ~= 8.30% annualised.
        $this->assertNotNull($result->computedTeg);
        $this->assertEqualsWithDelta(8.30, $result->computedTeg, 0.05);
    }

    public function test_teg_with_upfront_fees_is_higher_than_nominal(): void
    {
        $without = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 60,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );
        $with = new SimulationInput(
            principal: 10_000_000.0,
            annualRate: 8.0,
            termPeriods: 60,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
            dossierFeePct: 1.0,
        );

        $calc = new AmortizationCalculator;
        $tegWithout = $calc->compute($without)->computedTeg;
        $tegWith = $calc->compute($with)->computedTeg;

        $this->assertNotNull($tegWithout);
        $this->assertNotNull($tegWith);
        $this->assertGreaterThan($tegWithout, $tegWith);
    }
}
