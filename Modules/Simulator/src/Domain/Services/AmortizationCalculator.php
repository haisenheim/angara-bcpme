<?php

namespace Modules\Simulator\Domain\Services;

use Modules\Simulator\Domain\DTOs\ScheduleLineDto;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;

/**
 * Pure-PHP credit amortization calculator.
 *
 * No Laravel / framework dependency. Designed to be unit-testable in isolation.
 */
final class AmortizationCalculator
{
    public function __construct(
        private readonly TegCalculator $tegCalculator = new TegCalculator,
    ) {}

    public function compute(SimulationInput $input): SimulationResult
    {
        $lines = match ($input->amortizationType) {
            AmortizationType::CONSTANT => $this->computeConstant($input),
            AmortizationType::LINEAR => $this->computeLinear($input),
            AmortizationType::IN_FINE => $this->computeInFine($input),
        };

        $lines = $this->applyDates($input, $lines);

        $totalPrincipal = 0.0;
        $totalInterest = 0.0;
        $totalFees = 0.0;
        $totalInsurance = 0.0;
        $totalVat = 0.0;
        $totalDue = 0.0;
        $firstPayment = 0.0;
        $maxPayment = 0.0;

        foreach ($lines as $i => $line) {
            $totalPrincipal += $line->principalPaid;
            $totalInterest += $line->interestPaid;
            $totalFees += $line->feesPaid;
            $totalInsurance += $line->insurancePaid;
            $totalVat += $line->vatPaid;
            $totalDue += $line->totalPayment;
            if ($i === 0) {
                $firstPayment = $line->totalPayment;
            }
            if ($line->totalPayment > $maxPayment) {
                $maxPayment = $line->totalPayment;
            }
        }

        $teg = $this->tegCalculator->compute($input, $lines);

        return new SimulationResult(
            input: $input,
            lines: $lines,
            totalPrincipal: $totalPrincipal,
            totalInterest: $totalInterest,
            totalFees: $totalFees,
            totalInsurance: $totalInsurance,
            totalVat: $totalVat,
            totalDue: $totalDue,
            firstPayment: $firstPayment,
            maxPayment: $maxPayment,
            computedTeg: $teg,
        );
    }

    /**
     * Constant payment (annuites constantes) amortization.
     * During deferral, capital is not amortized; interest is paid (PARTIAL) or capitalized (TOTAL).
     *
     * @return list<ScheduleLineDto>
     */
    private function computeConstant(SimulationInput $input): array
    {
        $rate = $input->periodicRate();
        $deferral = $input->deferralPeriods;
        $isTotal = $input->deferralType === DeferralType::TOTAL;

        $balanceAfterDeferral = $input->principal;
        if ($deferral > 0 && $isTotal) {
            $balanceAfterDeferral = $input->principal * (1 + $rate) ** $deferral;
        }

        $amortPeriods = $input->amortizingPeriods();
        $constantPayment = $rate > 0
            ? $balanceAfterDeferral * $rate / (1 - (1 + $rate) ** (-$amortPeriods))
            : $balanceAfterDeferral / $amortPeriods;

        $lines = [];
        $balance = $input->principal;
        $totalFees = $input->totalDossierFees();

        for ($p = 1; $p <= $input->termPeriods; $p++) {
            $isDeferred = $p <= $deferral;
            $capitalStart = $balance;
            $interest = $balance * $rate;
            $principalPaid = 0.0;
            $feesPaid = $p === 1 ? $totalFees : 0.0;

            if ($isDeferred) {
                if ($isTotal) {
                    $balance += $interest;
                    $interest = 0.0;
                }
                // PARTIAL: interest is paid this period, capital unchanged.
                $payment = $interest;
            } else {
                $principalPaid = $constantPayment - $interest;
                $balance -= $principalPaid;
                $payment = $constantPayment;
            }

            if ($p === $input->termPeriods && abs($balance) < 0.01) {
                $balance = 0.0;
            }

            $insurance = $this->insuranceFor($input, $capitalStart);
            $vat = ($interest + $feesPaid) * $input->vatRate / 100.0;
            $totalPayment = $payment + $insurance + $feesPaid + $vat;

            $lines[] = new ScheduleLineDto(
                periodIndex: $p,
                periodDate: null,
                capitalDueStart: $capitalStart,
                principalPaid: $principalPaid,
                interestPaid: $interest,
                insurancePaid: $insurance,
                feesPaid: $feesPaid,
                vatPaid: $vat,
                totalPayment: $totalPayment,
                capitalDueEnd: $balance,
                isDeferred: $isDeferred,
            );
        }

        return $this->fixLastLineRounding($lines);
    }

    /**
     * Linear amortization: constant principal each period.
     *
     * @return list<ScheduleLineDto>
     */
    private function computeLinear(SimulationInput $input): array
    {
        $rate = $input->periodicRate();
        $deferral = $input->deferralPeriods;
        $isTotal = $input->deferralType === DeferralType::TOTAL;

        $balanceAfterDeferral = $input->principal;
        if ($deferral > 0 && $isTotal) {
            $balanceAfterDeferral = $input->principal * (1 + $rate) ** $deferral;
        }

        $constantPrincipal = $balanceAfterDeferral / $input->amortizingPeriods();

        $lines = [];
        $balance = $input->principal;
        $totalFees = $input->totalDossierFees();

        for ($p = 1; $p <= $input->termPeriods; $p++) {
            $isDeferred = $p <= $deferral;
            $capitalStart = $balance;
            $interest = $balance * $rate;
            $principalPaid = 0.0;
            $feesPaid = $p === 1 ? $totalFees : 0.0;

            if ($isDeferred) {
                if ($isTotal) {
                    $balance += $interest;
                    $interest = 0.0;
                }
                $payment = $interest;
            } else {
                $principalPaid = $constantPrincipal;
                $balance -= $principalPaid;
                $payment = $principalPaid + $interest;
            }

            if ($p === $input->termPeriods && abs($balance) < 0.01) {
                $balance = 0.0;
            }

            $insurance = $this->insuranceFor($input, $capitalStart);
            $vat = ($interest + $feesPaid) * $input->vatRate / 100.0;
            $totalPayment = $payment + $insurance + $feesPaid + $vat;

            $lines[] = new ScheduleLineDto(
                periodIndex: $p,
                periodDate: null,
                capitalDueStart: $capitalStart,
                principalPaid: $principalPaid,
                interestPaid: $interest,
                insurancePaid: $insurance,
                feesPaid: $feesPaid,
                vatPaid: $vat,
                totalPayment: $totalPayment,
                capitalDueEnd: $balance,
                isDeferred: $isDeferred,
            );
        }

        return $this->fixLastLineRounding($lines);
    }

    /**
     * In fine: interest only every period, principal repaid at maturity.
     *
     * @return list<ScheduleLineDto>
     */
    private function computeInFine(SimulationInput $input): array
    {
        $rate = $input->periodicRate();
        $deferral = $input->deferralPeriods;
        $isTotal = $input->deferralType === DeferralType::TOTAL;

        $lines = [];
        $balance = $input->principal;
        $totalFees = $input->totalDossierFees();

        for ($p = 1; $p <= $input->termPeriods; $p++) {
            $isDeferred = $p <= $deferral;
            $capitalStart = $balance;
            $interest = $balance * $rate;
            $principalPaid = 0.0;
            $feesPaid = $p === 1 ? $totalFees : 0.0;

            if ($isDeferred && $isTotal) {
                $balance += $interest;
                $interest = 0.0;
                $payment = 0.0;
            } else {
                if ($p === $input->termPeriods) {
                    $principalPaid = $balance;
                    $balance = 0.0;
                }
                $payment = $interest + $principalPaid;
            }

            $insurance = $this->insuranceFor($input, $capitalStart);
            $vat = ($interest + $feesPaid) * $input->vatRate / 100.0;
            $totalPayment = $payment + $insurance + $feesPaid + $vat;

            $lines[] = new ScheduleLineDto(
                periodIndex: $p,
                periodDate: null,
                capitalDueStart: $capitalStart,
                principalPaid: $principalPaid,
                interestPaid: $interest,
                insurancePaid: $insurance,
                feesPaid: $feesPaid,
                vatPaid: $vat,
                totalPayment: $totalPayment,
                capitalDueEnd: $balance,
                isDeferred: $isDeferred,
            );
        }

        return $lines;
    }

    private function insuranceFor(SimulationInput $input, float $capitalStart): float
    {
        if ($input->insurancePct <= 0) {
            return 0.0;
        }
        $base = $input->insuranceBasis === InsuranceBasis::INITIAL_PRINCIPAL
            ? $input->principal
            : $capitalStart;

        // insurance_pct is annual %; periodic insurance = base * pct/100 / periodsPerYear
        return $base * ($input->insurancePct / 100.0) / $input->periodicity->periodsPerYear();
    }

    /**
     * Apply dates to schedule lines based on first_period_date and periodicity.
     *
     * @param  list<ScheduleLineDto>  $lines
     * @return list<ScheduleLineDto>
     */
    private function applyDates(SimulationInput $input, array $lines): array
    {
        if ($input->firstPeriodDate === null) {
            return $lines;
        }

        $months = $input->periodicity->monthsPerPeriod();
        $base = $input->firstPeriodDate;

        $result = [];
        foreach ($lines as $line) {
            $offset = ($line->periodIndex - 1) * $months;
            $date = $base->modify('+'.$offset.' months');

            $result[] = new ScheduleLineDto(
                periodIndex: $line->periodIndex,
                periodDate: $date,
                capitalDueStart: $line->capitalDueStart,
                principalPaid: $line->principalPaid,
                interestPaid: $line->interestPaid,
                insurancePaid: $line->insurancePaid,
                feesPaid: $line->feesPaid,
                vatPaid: $line->vatPaid,
                totalPayment: $line->totalPayment,
                capitalDueEnd: $line->capitalDueEnd,
                isDeferred: $line->isDeferred,
            );
        }

        return $result;
    }

    /**
     * Adjust the last line so that capital_due_end == 0 exactly, absorbing rounding drift.
     *
     * @param  list<ScheduleLineDto>  $lines
     * @return list<ScheduleLineDto>
     */
    private function fixLastLineRounding(array $lines): array
    {
        if ($lines === []) {
            return $lines;
        }
        $lastIndex = count($lines) - 1;
        $last = $lines[$lastIndex];
        if (abs($last->capitalDueEnd) < 0.01) {
            return $lines;
        }

        $drift = $last->capitalDueEnd;
        $adjustedPrincipal = $last->principalPaid + $drift;
        $adjustedTotal = $last->totalPayment + $drift;

        $lines[$lastIndex] = new ScheduleLineDto(
            periodIndex: $last->periodIndex,
            periodDate: $last->periodDate,
            capitalDueStart: $last->capitalDueStart,
            principalPaid: $adjustedPrincipal,
            interestPaid: $last->interestPaid,
            insurancePaid: $last->insurancePaid,
            feesPaid: $last->feesPaid,
            vatPaid: $last->vatPaid,
            totalPayment: $adjustedTotal,
            capitalDueEnd: 0.0,
            isDeferred: $last->isDeferred,
        );

        return $lines;
    }
}
