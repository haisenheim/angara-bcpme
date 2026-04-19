<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BcpmeAngaraDemoOperationalSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BcpmeAngaraDemoOrganisationSeeder::class,
            BcpmeAngaraDemoPortfolioSeeder::class,
            BcpmeAngaraDemoScoringSeeder::class,
            BcpmeAngaraDemoUsersSeeder::class,
        ]);
    }
}
