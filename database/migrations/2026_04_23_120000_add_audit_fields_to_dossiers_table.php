<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'reng_responsable_avis_at')) {
                $table->timestamp('reng_responsable_avis_at')->nullable()->after('reng_responsable_avis');
            }
            if (! Schema::hasColumn('dossiers', 'reng_responsable_avis_by_user_id')) {
                $table->unsignedBigInteger('reng_responsable_avis_by_user_id')->nullable()->after('reng_responsable_avis_at');
            }

            if (! Schema::hasColumn('dossiers', 'rerx_responsable_avis_at')) {
                $table->timestamp('rerx_responsable_avis_at')->nullable()->after('rerx_responsable_avis');
            }
            if (! Schema::hasColumn('dossiers', 'rerx_responsable_avis_by_user_id')) {
                $table->unsignedBigInteger('rerx_responsable_avis_by_user_id')->nullable()->after('rerx_responsable_avis_at');
            }

            if (! Schema::hasColumn('dossiers', 'conclusions_ca_saved_at')) {
                $table->timestamp('conclusions_ca_saved_at')->nullable()->after('conclusions_ca');
            }
            if (! Schema::hasColumn('dossiers', 'conclusions_ca_saved_by_user_id')) {
                $table->unsignedBigInteger('conclusions_ca_saved_by_user_id')->nullable()->after('conclusions_ca_saved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'reng_responsable_avis_by_user_id',
                'reng_responsable_avis_at',
                'rerx_responsable_avis_by_user_id',
                'rerx_responsable_avis_at',
                'conclusions_ca_saved_by_user_id',
                'conclusions_ca_saved_at',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

