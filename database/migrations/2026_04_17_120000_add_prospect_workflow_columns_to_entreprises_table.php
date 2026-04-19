<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Parcours prospect BC-PME : brouillon → soumission explicite → avis juridique & conformité (horodatés).
     */
    public function up(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'prospect_submitted_at')) {
                $table->timestamp('prospect_submitted_at')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'juridique_avis')) {
                $table->text('juridique_avis')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'juridique_avis_at')) {
                $table->timestamp('juridique_avis_at')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'juridique_avis_user_id')) {
                $table->unsignedInteger('juridique_avis_user_id')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'conformite_avis')) {
                $table->text('conformite_avis')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'conformite_avis_at')) {
                $table->timestamp('conformite_avis_at')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'conformite_avis_user_id')) {
                $table->unsignedInteger('conformite_avis_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            $columns = [];
            foreach ([
                'prospect_submitted_at',
                'juridique_avis',
                'juridique_avis_at',
                'juridique_avis_user_id',
                'conformite_avis',
                'conformite_avis_at',
                'conformite_avis_user_id',
            ] as $column) {
                if (Schema::connection('central_app_mysql')->hasColumn('entreprises', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
