<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_user_id')) {
                $table->unsignedBigInteger('rerx_analyste_risques_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_assigned_at')) {
                $table->timestamp('rerx_analyste_risques_assigned_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_assigned_by_user_id')) {
                $table->unsignedBigInteger('rerx_analyste_risques_assigned_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyse_risques')) {
                $table->longText('rerx_analyse_risques')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_avis')) {
                $table->longText('rerx_analyste_risques_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_submitted_at')) {
                $table->timestamp('rerx_analyste_risques_submitted_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_analyste_risques_submitted_by_user_id')) {
                $table->unsignedBigInteger('rerx_analyste_risques_submitted_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_responsable_avis')) {
                $table->longText('rerx_responsable_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_submitted_to_direction_at')) {
                $table->timestamp('rerx_submitted_to_direction_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'rerx_submitted_to_direction_by_user_id')) {
                $table->unsignedBigInteger('rerx_submitted_to_direction_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'rerx_submitted_to_direction_by_user_id',
                'rerx_submitted_to_direction_at',
                'rerx_responsable_avis',
                'rerx_analyste_risques_submitted_by_user_id',
                'rerx_analyste_risques_submitted_at',
                'rerx_analyste_risques_avis',
                'rerx_analyse_risques',
                'rerx_analyste_risques_assigned_by_user_id',
                'rerx_analyste_risques_assigned_at',
                'rerx_analyste_risques_user_id',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
