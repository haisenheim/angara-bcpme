<?php

namespace Modules\Simulator\Domain\Enums;

enum InsuranceBasis: string
{
    case INITIAL_PRINCIPAL = 'initial_principal';
    case OUTSTANDING_BALANCE = 'outstanding_balance';

    public function label(): string
    {
        return match ($this) {
            self::INITIAL_PRINCIPAL => 'Sur capital initial',
            self::OUTSTANDING_BALANCE => 'Sur capital restant du',
        };
    }
}
