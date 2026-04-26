<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'chef_filiere_submitted_to_agence_at')) {
                $table->timestamp('chef_filiere_submitted_to_agence_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'chef_filiere_submitted_to_agence_by_user_id')) {
                $table->unsignedBigInteger('chef_filiere_submitted_to_agence_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach (['chef_filiere_submitted_to_agence_by_user_id', 'chef_filiere_submitted_to_agence_at'] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
