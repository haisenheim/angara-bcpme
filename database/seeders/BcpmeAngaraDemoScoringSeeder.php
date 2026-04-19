<?php

namespace Database\Seeders;

class BcpmeAngaraDemoScoringSeeder extends BcpmeAngaraDemoReferenceSeeder
{
    /**
     * @return list<string>
     */
    protected function referenceTables(): array
    {
        return [
            'criteres',
            'sous_criteres',
            'choices',
        ];
    }
}
