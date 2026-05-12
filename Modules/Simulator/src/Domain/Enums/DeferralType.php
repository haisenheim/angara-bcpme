<?php

namespace Modules\Simulator\Domain\Enums;

enum DeferralType: string
{
    case NONE = 'none';
    case PARTIAL = 'partial';
    case TOTAL = 'total';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'Aucun differe',
            self::PARTIAL => 'Differe partiel (interets seuls)',
            self::TOTAL => 'Differe total (capital + interets)',
        };
    }
}
