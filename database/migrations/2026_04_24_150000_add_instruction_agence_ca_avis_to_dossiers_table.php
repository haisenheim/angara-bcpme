<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'instruction_agence_ca_avis')) {
                $table->longText('instruction_agence_ca_avis')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_ca_avis_saved_at')) {
                $table->timestamp('instruction_agence_ca_avis_saved_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_ca_avis_saved_by_user_id')) {
                $table->unsignedBigInteger('instruction_agence_ca_avis_saved_by_user_id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'instruction_agence_ca_avis_saved_by_user_id',
                'instruction_agence_ca_avis_saved_at',
                'instruction_agence_ca_avis',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
