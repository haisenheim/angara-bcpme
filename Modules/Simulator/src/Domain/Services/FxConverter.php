<?php

namespace Modules\Simulator\Domain\Services;

/**
 * Pure-PHP FX converter. Stateless: caller provides the rate.
 */
final class FxConverter
{
    /**
     * Convert an amount from one currency to another given an explicit rate.
     *
     * @param  float  $rate  Multiplier from source to target. amount_target = amount_source * rate.
     */
    public function convert(float $amount, float $rate): float
    {
        if ($rate <= 0) {
            return $amount;
        }

        return $amount * $rate;
    }
}
