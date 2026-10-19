<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Les champs de structuration sont de longueur illimitée (LONGTEXT) et sont recopiés, concaténés,
 * dans l'avis « structuration » de l'analyse critique : la colonne contenu (TEXT, 64 Ko) devient LONGTEXT.
 */
return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (Schema::connection($this->c)->hasTable('analyse_critique_avis')) {
            DB::connection($this->c)->statement('ALTER TABLE analyse_critique_avis MODIFY contenu LONGTEXT NULL');
        }
    }

    public function down(): void
    {
        // Retour à TEXT : uniquement si aucun contenu ne dépasse 64 Ko (sinon troncature).
        if (Schema::connection($this->c)->hasTable('analyse_critique_avis')
            && ! DB::connection($this->c)->table('analyse_critique_avis')->whereRaw('LENGTH(contenu) > 65535')->exists()) {
            DB::connection($this->c)->statement('ALTER TABLE analyse_critique_avis MODIFY contenu TEXT NULL');
        }
    }
};
