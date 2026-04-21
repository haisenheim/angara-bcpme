<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Référentiel BC-PME (profil_modifs.txt). Remplace le contenu de la table profils.
 *
 * Les utilisateurs existants peuvent encore porter d’anciens role_id : prévoir un
 * remapping métier séparé avant/après cette migration.
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

        $c = DB::connection($this->connection);
        $c->table('profils')->delete();

        $rows = [
            [1, 'Super Administrateur', 'Super Ad.', 0, 0, 0, 1],
            [2, 'PRESIDENT DU CONSEIL D\'ADMINISTRATION', 'PCA', 0, 1, 0, 1],
            [3, 'ADMINISTRATEUR', 'ADM', 0, 1, 0, 1],
            [4, 'DIRECTEUR GENERAL', 'DG', 1, 1, 0, 1],
            [5, 'DIRECTEUR GENERAL ADJOINT', 'DGA', 1, 1, 0, 1],
            [6, 'RESPONSABLE EXPLOITATION', 'RESP-EXP', 2, 1, 0, 1],
            [7, 'RESPONSABLE AUDIT INTERNE', 'RESP-AUD', 2, 1, 0, 1],
            [8, 'RESPONSABLE CONTROLE INTERNE', 'RESP-CI', 2, 1, 0, 1],
            [9, 'RESPONSABLE ENGAGEMENTS', 'RENG', 2, 1, 0, 1],
            [10, 'RESPONSABLE JURIDIQUE', 'REJU', 2, 1, 0, 1],
            [11, 'RESPONSABLE CONFORMITE', 'RECONF', 2, 1, 0, 1],
            [12, 'RESPONSABLE RISQUES', 'RERX', 2, 1, 0, 1],
            [14, 'RESPONSABLE REGIONAL', 'RESP-REG', 3, 1, 0, 1],
            [15, 'CHEF D\'AGENCE', 'CA', 3, 1, 0, 1],
            [16, 'GESTIONNAIRE', 'GEST', 4, 1, 0, 1],
            [17, 'ANALYSTE FINANCIER EXPLOITATION', 'AFE', 4, 1, 0, 1],
            [18, 'ANALYSTE RISQUES', 'ANRX', 4, 1, 0, 1],
            [19, 'ANALYSTE JURIDIQUE', 'ANJ', 4, 1, 0, 1],
            [20, 'ANALYSTE CREDIT', 'ANCDT', 4, 1, 0, 1],
            [21, 'ANALYSTE CONFORMITE', 'ANCONF', 4, 1, 0, 1],
            [22, 'AUDITEUR', 'AUD', 4, 1, 0, 1],
            [23, 'CONTROLEUR', 'CONT', 4, 1, 0, 1],
            [24, 'CHEF DE FILIERE', 'CHFIL', 4, 1, 0, 1],
        ];

        foreach ($rows as $r) {
            $c->table('profils')->insert([
                'id' => $r[0],
                'name' => $r[1],
                'abb' => $r[2],
                'niveau' => $r[3],
                'metier' => $r[4],
                'programme' => $r[5],
                'active' => $r[6],
            ]);
        }
    }

    public function down(): void
    {
        // Pas de restauration automatique de l’ancien référentiel.
    }
};
