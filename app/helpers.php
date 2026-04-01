<?php

declare(strict_types=1);

use App\Models\Tenant;

if (! function_exists('tenant')) {
    /**
     * Coopérative courante (contexte « portail coop »), ou null hors de ce contexte.
     */
    function tenant(?string $key = null): mixed
    {
        if (! app()->bound('tenant')) {
            return $key === null ? null : null;
        }

        /** @var Tenant $t */
        $t = app('tenant');

        return $key === null ? $t : $t->{$key};
    }
}
