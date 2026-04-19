<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'latitude')) {
                $table->string('latitude', 100)->nullable()->after('arrondissement_id');
            }

            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'longitude')) {
                $table->string('longitude', 100)->nullable()->after('latitude');
            }

            if (! Schema::connection('central_app_mysql')->hasColumn('entreprises', 'chiffre_affaire')) {
                $table->double('chiffre_affaire')->nullable()->after('capital');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('central_app_mysql')->table('entreprises', function (Blueprint $table) {
            $columns = [];

            foreach (['latitude', 'longitude', 'chiffre_affaire'] as $column) {
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
