<?php

namespace App\Concerns;

trait ParsesProgrammeRelationIds
{
    /**
     * Convertit une liste d'IDs séparés par des virgules (ex. comboTree) en entiers strictement positifs.
     * Évite les insertions avec '' ou 0 lorsque le champ caché est vide ou mal formé.
     *
     * @return int[]
     */
    protected function intIdsFromCommaList(?string $value): array
    {
        if ($value === null || trim($value) === '') {
            return [];
        }

        $parts = array_map('trim', explode(',', $value));
        $ids = array_map(static fn ($p) => (int) $p, $parts);

        return array_values(array_filter($ids, static fn (int $id) => $id > 0));
    }
}
