<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Passage prospect → client (BC-PME), complète 2026_04_17_120000. */
return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'promu_client_at')) {
                $table->timestamp('promu_client_at')->nullable();
            }
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'promu_client_user_id')) {
                $table->unsignedInteger('promu_client_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            $cols = [];
            if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'promu_client_at')) {
                $cols[] = 'promu_client_at';
            }
            if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'promu_client_user_id')) {
                $cols[] = 'promu_client_user_id';
            }
            if ($cols !== []) {
                $table->dropColumn($cols);
            }
        });
    }
};
