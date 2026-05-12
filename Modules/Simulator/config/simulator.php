<?php

return [

    'connection' => env('SIMULATOR_DB_CONNECTION', 'central_app_mysql'),

    'route_prefix' => 'simulator',

    'currencies' => [
        'XOF' => ['label' => 'Franc CFA BCEAO (XOF)', 'decimals' => 0],
        'XAF' => ['label' => 'Franc CFA BEAC (XAF)', 'decimals' => 0],
        'EUR' => ['label' => 'Euro (EUR)', 'decimals' => 2],
        'USD' => ['label' => 'Dollar US (USD)', 'decimals' => 2],
    ],

    'default_currency' => env('SIMULATOR_DEFAULT_CURRENCY', 'XAF'),

    'periodicities' => [
        'monthly' => 12,
        'quarterly' => 4,
        'semestrial' => 2,
        'annual' => 1,
    ],

    'amortization_types' => [
        'constant',
        'linear',
        'in_fine',
    ],

    'usury_rate_warning' => (float) env('SIMULATOR_USURY_RATE', 17.0),

    /** Profils métier ANGARA habilités à ouvrir le simulateur (alignés sur la fiche client). */
    'allowed_roles' => array_values(array_unique(array_filter(array_map(
        static fn (mixed $id): int => (int) $id,
        [
            config('angara.role_dg'),
            config('angara.role_dga'),
            config('angara.role_responsable_exploitation'),
            config('angara.role_responsable_audit_interne'),
            config('angara.role_responsable_controle_interne'),
            config('angara.role_responsable_engagements'),
            config('angara.role_responsable_juridique'),
            config('angara.role_responsable_conformite'),
            config('angara.role_responsable_risques'),
            config('angara.role_responsable_regional'),
            config('angara.role_chef_agence'),
            config('angara.role_gestionnaire'),
            config('angara.role_analyste_financier'),
            config('angara.role_analyste_risques'),
            config('angara.role_analyste_juridique'),
            config('angara.role_analyste_credit'),
            config('angara.role_analyste_conformite'),
            config('angara.role_auditeur'),
            config('angara.role_controleur'),
            config('angara.role_chef_filiere'),
        ],
    )))),

    'precision' => [
        'amount' => 2,
        'rate' => 6,
        'teg_tolerance' => 1.0e-7,
        'teg_max_iterations' => 200,
    ],

];
