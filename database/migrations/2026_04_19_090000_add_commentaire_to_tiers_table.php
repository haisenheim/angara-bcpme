<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('tiers')) {
            return;
        }

        Schema::connection('central_app_mysql')->table('tiers', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('tiers', 'commentaire')) {
                $table->text('commentaire')->nullable()->after('lien');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('tiers')) {
            return;
        }

        Schema::connection('central_app_mysql')->table('tiers', function (Blueprint $table) {
            if (Schema::connection('central_app_mysql')->hasColumn('tiers', 'commentaire')) {
                $table->dropColumn('commentaire');
            }
        });
    }
};
