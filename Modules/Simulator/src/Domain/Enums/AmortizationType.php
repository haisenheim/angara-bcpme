<?php

namespace Modules\Simulator\Domain\Enums;

enum AmortizationType: string
{
    case CONSTANT = 'constant';
    case LINEAR = 'linear';
    case IN_FINE = 'in_fine';

    public function label(): string
    {
        return match ($this) {
            self::CONSTANT => 'Echeances constantes',
            self::LINEAR => 'Capital constant',
            self::IN_FINE => 'In fine',
        };
    }
}
