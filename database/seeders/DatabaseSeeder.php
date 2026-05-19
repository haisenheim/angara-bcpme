<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SmeNotesSeeder::class);

        if (filter_var(env('BCPME_SEED_REFERENCE', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->call(BcpmeAngaraDemoReferenceSeeder::class);
            $this->call(OrganisationEntitesSeeder::class);
        }
    }
}
