<?php

namespace Database\Seeders;

use App\Models\OrganisationEntite;
use Illuminate\Database\Seeder;

class OrganisationEntitesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // Pôles
            ['key' => 'pole_exploitation', 'name' => 'Pôle exploitation', 'type' => 'pole', 'route_prefix' => 'respexp', 'active' => true],
            ['key' => 'pole_juridique', 'name' => 'Pôle juridique', 'type' => 'pole', 'route_prefix' => 'juridique', 'active' => true],
            ['key' => 'pole_engagements', 'name' => 'Pôle engagements', 'type' => 'pole', 'route_prefix' => 'reng', 'active' => true],
            ['key' => 'pole_risques', 'name' => 'Pôle risques', 'type' => 'pole', 'route_prefix' => 'rerx', 'active' => true],

            // Gouvernance
            ['key' => 'direction_generale', 'name' => 'Direction générale', 'type' => 'direction_generale', 'route_prefix' => 'dg', 'active' => true],
            ['key' => 'conseil_administration', 'name' => 'Conseil d’administration', 'type' => 'conseil_administration', 'route_prefix' => null, 'active' => true],

            // Contrôle
            ['key' => 'audit_interne', 'name' => 'Audit interne', 'type' => 'audit_ci', 'route_prefix' => 'respaud', 'active' => true],
            ['key' => 'controle_interne', 'name' => 'Contrôle interne', 'type' => 'audit_ci', 'route_prefix' => 'respci', 'active' => true],
        ];

        foreach ($rows as $row) {
            OrganisationEntite::query()->updateOrCreate(
                ['key' => $row['key']],
                [
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'route_prefix' => $row['route_prefix'],
                    'active' => (bool) $row['active'],
                ]
            );
        }
    }
}

