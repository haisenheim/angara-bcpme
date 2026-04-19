<?php

namespace Database\Seeders;

class BcpmeAngaraDemoOrganisationSeeder extends BcpmeAngaraDemoReferenceSeeder
{
    /**
     * @return list<string>
     */
    protected function referenceTables(): array
    {
        return [
            'representations',
            'agences',
            'profils',
        ];
    }
}
