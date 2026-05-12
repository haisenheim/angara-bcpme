<?php

namespace Modules\Simulator\Domain\DTOs;

final class SimulationResult
{
    /**
     * @param  list<ScheduleLineDto>  $lines
     */
    public function __construct(
        public readonly SimulationInput $input,
        public readonly array $lines,
        public readonly float $totalPrincipal,
        public readonly float $totalInterest,
        public readonly float $totalFees,
        public readonly float $totalInsurance,
        public readonly float $totalVat,
        public readonly float $totalDue,
        public readonly float $firstPayment,
        public readonly float $maxPayment,
        public readonly ?float $computedTeg,
    ) {}

    public function toArray(): array
    {
        return [
            'input' => $this->input->toArray(),
            'lines' => array_map(static fn (ScheduleLineDto $l) => $l->toArray(), $this->lines),
            'totals' => [
                'principal' => round($this->totalPrincipal, 2),
                'interest' => round($this->totalInterest, 2),
                'fees' => round($this->totalFees, 2),
                'insurance' => round($this->totalInsurance, 2),
                'vat' => round($this->totalVat, 2),
                'total_due' => round($this->totalDue, 2),
                'first_payment' => round($this->firstPayment, 2),
                'max_payment' => round($this->maxPayment, 2),
                'computed_teg' => $this->computedTeg !== null ? round($this->computedTeg, 4) : null,
            ],
        ];
    }
}
