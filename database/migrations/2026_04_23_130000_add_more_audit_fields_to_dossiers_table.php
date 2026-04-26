<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'instruction_grille_last_edited_at')) {
                $table->timestamp('instruction_grille_last_edited_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_grille_last_edited_by_user_id')) {
                $table->unsignedBigInteger('instruction_grille_last_edited_by_user_id')->nullable();
            }

            if (! Schema::hasColumn('dossiers', 'juridique_analyste_avis_saved_at')) {
                $table->timestamp('juridique_analyste_avis_saved_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_avis_saved_by_user_id')) {
                $table->unsignedBigInteger('juridique_analyste_avis_saved_by_user_id')->nullable();
            }

            if (! Schema::hasColumn('dossiers', 'juridique_responsable_avis_at')) {
                $table->timestamp('juridique_responsable_avis_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_responsable_avis_by_user_id')) {
                $table->unsignedBigInteger('juridique_responsable_avis_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'instruction_grille_last_edited_by_user_id',
                'instruction_grille_last_edited_at',
                'juridique_analyste_avis_saved_by_user_id',
                'juridique_analyste_avis_saved_at',
                'juridique_responsable_avis_by_user_id',
                'juridique_responsable_avis_at',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
