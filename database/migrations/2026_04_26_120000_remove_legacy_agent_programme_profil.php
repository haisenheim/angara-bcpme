<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Supprime le dernier vestige du profil « AGENT PROGRAMME » sur les bases
 * qui n’ont pas encore été entièrement réalignées sur le référentiel BC-PME.
 */
return new class extends Migration
{
    /** @var string */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->connection)->hasTable('profils')) {
            return;
        }

        $c = DB::connection($this->connection);

        $c->table('profils')->where('name', 'AGENT PROGRAMME')->delete();

        if (Schema::connection($this->connection)->hasTable('users')) {
            $c->table('users')->where('email', 'user.program@angara.com')->delete();
        }
    }

    public function down(): void
    {
        // Le profil n’est plus référencé dans le référentiel BC-PME.
    }
};
