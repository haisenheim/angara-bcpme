<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('dossiers', 'conclusions_ca')) {
            return;
        }
        // Fix updated_at if invalid (MySQL strict mode) before altering
        DB::statement("ALTER TABLE dossiers MODIFY updated_at TIMESTAMP NULL DEFAULT NULL");
        Schema::table('dossiers', function (Blueprint $table) {
            $table->text('conclusions_ca')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('dossiers', 'conclusions_ca')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $table->dropColumn('conclusions_ca');
            });
        }
    }
};
