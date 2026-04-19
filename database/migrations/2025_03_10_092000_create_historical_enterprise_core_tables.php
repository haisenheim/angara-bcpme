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
            'entreprises',
            'persons',
            'fichiers',
            'tiers',
            'entreprises_elements_constitutifs',
            'entreprise_appuis',
            'entreprise_produits',
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
