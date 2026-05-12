<?php

namespace Modules\Simulator\Domain\Enums;

enum ScenarioStatus: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case VALIDATED = 'validated';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Brouillon',
            self::SUBMITTED => 'Soumis',
            self::VALIDATED => 'Valide',
            self::REJECTED => 'Rejete',
        };
    }

    public function isLocked(): bool
    {
        return in_array($this, [self::SUBMITTED, self::VALIDATED, self::REJECTED], true);
    }
}
