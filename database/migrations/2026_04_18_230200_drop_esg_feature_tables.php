<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'dossier_esg_evaluation_items',
            'dossier_esg_evaluations',
            'entreprise_evaluation_profiles',
            'evaluation_indicators',
            'evaluation_categories',
            'evaluation_score_thresholds',
            'evaluation_settings',
            'evaluation_frameworks',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Le module ESG a ete retire du projet.
    }
};
