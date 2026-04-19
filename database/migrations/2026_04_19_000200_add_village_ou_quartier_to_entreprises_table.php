<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'village_ou_quartier')) {
                $table->string('village_ou_quartier', 255)->nullable()->after('arrondissement_id');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'village_ou_quartier')) {
                $table->dropColumn('village_ou_quartier');
            }
        });
    }
};
