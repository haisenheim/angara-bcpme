<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Refus prospect par le chef d'agence : verrouillage des avis (comme après validation).
     */
    public function up(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'prospect_rejected_at')) {
                $table->timestamp('prospect_rejected_at')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'prospect_rejected_user_id')) {
                $table->unsignedInteger('prospect_rejected_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            foreach (['prospect_rejected_at', 'prospect_rejected_user_id'] as $column) {
                if (Schema::connection('central_app_mysql')->hasColumn('entreprises', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
