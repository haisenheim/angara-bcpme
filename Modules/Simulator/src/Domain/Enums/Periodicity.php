<?php

namespace Modules\Simulator\Domain\Enums;

enum Periodicity: string
{
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case SEMESTRIAL = 'semestrial';
    case ANNUAL = 'annual';

    public function periodsPerYear(): int
    {
        return match ($this) {
            self::MONTHLY => 12,
            self::QUARTERLY => 4,
            self::SEMESTRIAL => 2,
            self::ANNUAL => 1,
        };
    }

    public function monthsPerPeriod(): int
    {
        return (int) (12 / $this->periodsPerYear());
    }

    public function label(): string
    {
        return match ($this) {
            self::MONTHLY => 'Mensuelle',
            self::QUARTERLY => 'Trimestrielle',
            self::SEMESTRIAL => 'Semestrielle',
            self::ANNUAL => 'Annuelle',
        };
    }
}
