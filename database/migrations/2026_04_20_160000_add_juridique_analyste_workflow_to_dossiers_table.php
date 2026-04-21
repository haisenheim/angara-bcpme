<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_user_id')) {
                $table->unsignedBigInteger('juridique_analyste_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_assigned_at')) {
                $table->timestamp('juridique_analyste_assigned_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_assigned_by_user_id')) {
                $table->unsignedBigInteger('juridique_analyste_assigned_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_avis')) {
                $table->longText('juridique_analyste_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_submitted_to_reju_at')) {
                $table->timestamp('juridique_analyste_submitted_to_reju_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_analyste_submitted_to_reju_by_user_id')) {
                $table->unsignedBigInteger('juridique_analyste_submitted_to_reju_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_responsable_avis')) {
                $table->longText('juridique_responsable_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_submitted_to_engagements_at')) {
                $table->timestamp('juridique_submitted_to_engagements_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'juridique_submitted_to_engagements_by_user_id')) {
                $table->unsignedBigInteger('juridique_submitted_to_engagements_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'juridique_submitted_to_engagements_by_user_id',
                'juridique_submitted_to_engagements_at',
                'juridique_responsable_avis',
                'juridique_analyste_submitted_to_reju_by_user_id',
                'juridique_analyste_submitted_to_reju_at',
                'juridique_analyste_avis',
                'juridique_analyste_assigned_by_user_id',
                'juridique_analyste_assigned_at',
                'juridique_analyste_user_id',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
