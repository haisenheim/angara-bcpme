<?php

namespace Database\Seeders;

class BcpmeAngaraDemoPortfolioSeeder extends BcpmeAngaraDemoReferenceSeeder
{
    /**
     * @return list<string>
     */
    protected function referenceTables(): array
    {
        return [
            'filieres',
            'produits',
        ];
    }
}
