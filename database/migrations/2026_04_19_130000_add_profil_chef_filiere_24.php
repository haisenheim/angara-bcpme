<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Référentiel BC-PME : profil « Chef de filière » (id 24).
 * Pour les bases déjà migrées sans cette ligne.
 */
return new class extends Migration
{
    /** @var string|null */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('profils')) {
            return;
        }

        DB::connection($this->connection)->table('profils')->updateOrInsert(
            ['id' => 24],
            [
                'name' => 'CHEF DE FILIERE',
                'abb' => 'CHFIL',
                'niveau' => 4,
                'metier' => 1,
                'programme' => 0,
                'active' => 1,
            ]
        );
    }

    public function down(): void
    {
        if (! Schema::connection($this->connection)->hasTable('profils')) {
            return;
        }

        DB::connection($this->connection)->table('profils')->where('id', 24)->delete();
    }
};
