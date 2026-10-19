<?php

namespace App\Support\Inertia;

/**
 * Conversion des variantes Bootstrap héritées (badge_variant des modèles) vers les variantes
 * du composant Vue StatusBadge : neutral | info | success | warning | danger.
 */
final class Badge
{
    public static function variant(?string $bootstrap): string
    {
        return match ($bootstrap) {
            'success' => 'success',
            'danger' => 'danger',
            'warning' => 'warning',
            'info', 'primary' => 'info',
            default => 'neutral',
        };
    }

    /**
     * @param  array{label?: string, badge_variant?: string, detail?: ?string, code?: string}  $presentation
     */
    public static function fromPresentation(array $presentation): array
    {
        return [
            'badge' => $presentation['label'] ?? '—',
            'variant' => self::variant($presentation['badge_variant'] ?? null),
            'detail' => $presentation['detail'] ?? null,
            'code' => $presentation['code'] ?? null,
        ];
    }
}
