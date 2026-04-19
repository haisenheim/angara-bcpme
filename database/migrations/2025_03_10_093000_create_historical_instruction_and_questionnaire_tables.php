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
            'programmes',
            'programme_appuis',
            'programme_indicateurs',
            'programme_organismes',
            'programme_produits',
            'composantes',
            'dossiers',
            'engagements',
            'engagement_entreprises',
            'criteres',
            'sous_criteres',
            'choices',
            'questions_sous_criteres',
            'questions',
            'questions_choices',
            'questions_answers',
            'reponses',
            'sme_notes',
            'indicateurs',
            'indicateurs_financiers',
            'critere_programme_ponderations',
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
