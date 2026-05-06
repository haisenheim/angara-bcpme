<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprise_equipe_membres')) {
            return;
        }

        Schema::connection('central_app_mysql')->table('entreprise_equipe_membres', function (Blueprint $table) {
            if (! Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'dirigeant')) {
                $table->boolean('dirigeant')->default(false)->after('associe');
            }

            if (! Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'cni_numero')) {
                $table->string('cni_numero', 80)->nullable()->after('dirigeant');
            }

            if (! Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'cni_expire_at')) {
                $table->date('cni_expire_at')->nullable()->after('cni_numero');
            }

            if (! Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'cni_fichier_id')) {
                $table->unsignedInteger('cni_fichier_id')->nullable()->index()->after('cni_expire_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprise_equipe_membres')) {
            return;
        }

        Schema::connection('central_app_mysql')->table('entreprise_equipe_membres', function (Blueprint $table) {
            $columns = [];
            foreach (['cni_fichier_id', 'cni_expire_at', 'cni_numero', 'dirigeant'] as $column) {
                if (Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', $column)) {
                    $columns[] = $column;
                }
            }
            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

