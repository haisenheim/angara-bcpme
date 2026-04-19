<?php

use App\Support\ReferenceDumpSchemaLoader;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'central_app_mysql';

    /**
     * @return list<string>
     */
    protected function tables(): array
    {
        return [
            'representations',
            'agences',
            'regions',
            'departements',
            'arrondissements',
            'villages',
            'quartiers',
            'localites',
            'tailles',
            'liens',
            'niveaux',
            'formes_juridiques',
            'entreprise_types',
            'profils',
            'approches',
            'tservices',
            'services',
            'banques',
            'Torganismes',
            'organismes',
            'fichiers_types',
            'elements_constitutifs_types',
            'branches',
            'filieres',
            'produits',
        ];
    }

    public function up(): void
    {
        $loader = new ReferenceDumpSchemaLoader($this->connection);
        $loader->createTables($this->tables());
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables()) as $table) {
            Schema::connection($this->connection)->dropIfExists($table);
        }
    }
};
