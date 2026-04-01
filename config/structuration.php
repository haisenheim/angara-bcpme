<?php

declare(strict_types=1);

/**
 * Remplace l’ancienne config Stancl (multi-base par coopérative, domaines centraux).
 */
return [

    'tenant_database_prefix' => env('STRUCTURATION_TENANT_DB_PREFIX', env('TENANCY_PREFIX', 'angara_')),
    'tenant_database_suffix' => env('STRUCTURATION_TENANT_DB_SUFFIX', env('TENANCY_SUFFIX', '_db')),

    /**
     * Hôtes HTTP pour l’application « centrale » (instruction, admin programme, etc.).
     * Les autres hôtes (sous-domaines coop, IP dédiée, etc.) sont résolus via la table domains.
     */
    'central_domains' => array_values(array_filter(array_unique(array_merge(
        array_filter([parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)]),
        array_filter(array_map('trim', explode(',', (string) env('STRUCTURATION_CENTRAL_EXTRA', '')))),
    )))) ?: ['localhost'],
];
