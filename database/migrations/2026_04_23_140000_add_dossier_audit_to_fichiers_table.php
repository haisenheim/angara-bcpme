<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pièces jointes au dossier : lien vers le dossier, horodatage et auteur du dépôt.
 */
return new class extends Migration
{
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('fichiers', function (Blueprint $table) use ($c) {
            if (! Schema::connection($c)->hasColumn('fichiers', 'dossier_id')) {
                $table->unsignedInteger('dossier_id')->nullable()->after('entreprise_id');
                $table->index('dossier_id');
            }
            if (! Schema::connection($c)->hasColumn('fichiers', 'original_name')) {
                $table->string('original_name', 255)->nullable()->after('name');
            }
            if (! Schema::connection($c)->hasColumn('fichiers', 'uploaded_at')) {
                $table->timestamp('uploaded_at')->nullable()->after('token');
            }
            if (! Schema::connection($c)->hasColumn('fichiers', 'uploaded_by_user_id')) {
                $table->unsignedBigInteger('uploaded_by_user_id')->nullable()->after('uploaded_at');
            }
        });
    }

    public function down(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('fichiers', function (Blueprint $table) use ($c) {
            if (Schema::connection($c)->hasColumn('fichiers', 'dossier_id')) {
                $table->dropIndex(['dossier_id']);
            }
        });

        Schema::connection($c)->table('fichiers', function (Blueprint $table) use ($c) {
            foreach (['uploaded_by_user_id', 'uploaded_at', 'original_name', 'dossier_id'] as $col) {
                if (Schema::connection($c)->hasColumn('fichiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
