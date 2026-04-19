<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'translations',
            'menu_items',
            'menus',
            'data_rows',
            'data_types',
            'postes',
            'secteurs',
            'gammes',
            'domaines',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Tables explicitement retirees du socle projet.
    }
};
