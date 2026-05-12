<?php

namespace Modules\Simulator\Domain\DTOs;

use DateTimeImmutable;

final class ScheduleLineDto
{
    public function __construct(
        public readonly int $periodIndex,
        public readonly ?DateTimeImmutable $periodDate,
        public readonly float $capitalDueStart,
        public readonly float $principalPaid,
        public readonly float $interestPaid,
        public readonly float $insurancePaid,
        public readonly float $feesPaid,
        public readonly float $vatPaid,
        public readonly float $totalPayment,
        public readonly float $capitalDueEnd,
        public readonly bool $isDeferred,
    ) {}

    public function toArray(): array
    {
        return [
            'period_index' => $this->periodIndex,
            'period_date' => $this->periodDate?->format('Y-m-d'),
            'capital_due_start' => round($this->capitalDueStart, 2),
            'principal_paid' => round($this->principalPaid, 2),
            'interest_paid' => round($this->interestPaid, 2),
            'insurance_paid' => round($this->insurancePaid, 2),
            'fees_paid' => round($this->feesPaid, 2),
            'vat_paid' => round($this->vatPaid, 2),
            'total_payment' => round($this->totalPayment, 2),
            'capital_due_end' => round($this->capitalDueEnd, 2),
            'is_deferred' => $this->isDeferred,
        ];
    }
}
