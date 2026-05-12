<?php

namespace Modules\Simulator\Domain\Services;

use Modules\Simulator\Domain\DTOs\ScheduleLineDto;
use Modules\Simulator\Domain\DTOs\SimulationInput;

/**
 * Compute the Taux Effectif Global (TEG/TAEG) by solving for the IRR
 * of net cash flows perceived by the borrower (principal received minus up-front fees)
 * and total payments returned over the schedule, using bisection.
 *
 * Returns the TEG as an annualized PERCENT (e.g. 8.45 means 8.45%).
 */
final class TegCalculator
{
    public function __construct(
        private readonly float $tolerance = 1.0e-7,
        private readonly int $maxIterations = 200,
    ) {}

    /**
     * @param  list<ScheduleLineDto>  $lines
     */
    public function compute(SimulationInput $input, array $lines): ?float
    {
        if ($lines === []) {
            return null;
        }

        $upfrontFees = $input->totalDossierFees();
        $netReceived = $input->principal - $upfrontFees;
        if ($netReceived <= 0) {
            return null;
        }

        // Build cash flows: index 0 = -netReceived (borrower point of view),
        // then for each line, the gross payment less the dossier fees already paid this period
        // (we keep the fees in the outflow because they are an actual cash cost).
        $flows = [-$netReceived];
        foreach ($lines as $line) {
            // The outflow is everything paid during the period.
            // Note: the upfront fees are subtracted from netReceived, so we must NOT also
            // subtract them from the outflow (they have to remain in the cost of the loan).
            $flows[] = $line->totalPayment;
        }

        // Solve npv(rate) = 0 by bisection on periodic rate.
        $low = -0.99;
        $high = 1.0;

        $npvLow = $this->npv($flows, $low);
        $npvHigh = $this->npv($flows, $high);

        // Expand range if needed.
        $expand = 0;
        while ($npvLow * $npvHigh > 0 && $expand < 5) {
            $high *= 2;
            $npvHigh = $this->npv($flows, $high);
            $expand++;
        }
        if ($npvLow * $npvHigh > 0) {
            return null;
        }

        $mid = 0.0;
        for ($i = 0; $i < $this->maxIterations; $i++) {
            $mid = ($low + $high) / 2.0;
            $npvMid = $this->npv($flows, $mid);

            if (abs($npvMid) < $this->tolerance) {
                break;
            }

            if ($npvLow * $npvMid < 0) {
                $high = $mid;
                $npvHigh = $npvMid;
            } else {
                $low = $mid;
                $npvLow = $npvMid;
            }
        }

        $annual = (1 + $mid) ** $input->periodicity->periodsPerYear() - 1;

        return $annual * 100.0;
    }

    /**
     * Net Present Value of cash flows given a periodic rate.
     *
     * @param  list<float>  $flows
     */
    private function npv(array $flows, float $rate): float
    {
        $sum = 0.0;
        foreach ($flows as $t => $flow) {
            $sum += $flow / (1 + $rate) ** $t;
        }

        return $sum;
    }
}
