<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Avis du chef de filière, obligatoire à la soumission du dossier d'instruction au chef d'agence
 * (chaque responsable du circuit d'instruction saisit son avis avant de valider son étape).
 */
return new class extends Migration
{
    /** @var string */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        if (Schema::connection($this->connection)->hasColumn('dossiers', 'chef_filiere_avis')) {
            return;
        }

        Schema::connection($this->connection)->table('dossiers', function (Blueprint $table) {
            $table->longText('chef_filiere_avis')->nullable()->after('chef_filiere_submitted_to_agence_by_user_id');
        });
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasColumn('dossiers', 'chef_filiere_avis')) {
            return;
        }

        Schema::connection($this->connection)->table('dossiers', function (Blueprint $table) {
            $table->dropColumn('chef_filiere_avis');
        });
    }
};
