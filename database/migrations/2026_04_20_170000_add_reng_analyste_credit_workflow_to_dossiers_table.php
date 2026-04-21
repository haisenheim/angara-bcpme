<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_user_id')) {
                $table->unsignedBigInteger('reng_analyste_credit_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_assigned_at')) {
                $table->timestamp('reng_analyste_credit_assigned_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_assigned_by_user_id')) {
                $table->unsignedBigInteger('reng_analyste_credit_assigned_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_contre_analyse')) {
                $table->longText('reng_contre_analyse')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_avis')) {
                $table->longText('reng_analyste_credit_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_etat_engagements_client')) {
                $table->longText('reng_etat_engagements_client')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_submitted_at')) {
                $table->timestamp('reng_analyste_credit_submitted_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_analyste_credit_submitted_by_user_id')) {
                $table->unsignedBigInteger('reng_analyste_credit_submitted_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_responsable_avis')) {
                $table->longText('reng_responsable_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_submitted_to_risques_at')) {
                $table->timestamp('reng_submitted_to_risques_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'reng_submitted_to_risques_by_user_id')) {
                $table->unsignedBigInteger('reng_submitted_to_risques_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'reng_submitted_to_risques_by_user_id',
                'reng_submitted_to_risques_at',
                'reng_responsable_avis',
                'reng_analyste_credit_submitted_by_user_id',
                'reng_analyste_credit_submitted_at',
                'reng_etat_engagements_client',
                'reng_analyste_credit_avis',
                'reng_contre_analyse',
                'reng_analyste_credit_assigned_by_user_id',
                'reng_analyste_credit_assigned_at',
                'reng_analyste_credit_user_id',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
