<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mécanisme de « rejet inter-pôles » (prompt l. 220).
 *
 * Permet à chaque responsable de pôle de rejeter le dossier reçu et de le renvoyer au pôle
 * précédent (le responsable précédent peut alors corriger son avis et retransmettre).
 *
 * 4 transitions :
 * - RJU vers REXP                         : juridique_rejected_to_exploitation_*
 * - RENG vers RJU                         : engagements_rejected_to_juridique_*
 * - RISQ vers RENG                        : risques_rejected_to_engagements_*
 * - Direction (DG/DGA/délégué) vers RISQ  : direction_rejected_to_risques_*
 *
 * À la retransmission, les flags de rejet sont remis à NULL (l'événement reste en timeline).
 */
return new class extends Migration
{
    /** @var string */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            $columns = [
                // RJU rejette vers REXP
                'juridique_rejected_to_exploitation_at' => 'timestamp',
                'juridique_rejected_to_exploitation_by_user_id' => 'unsignedBigInteger',
                'juridique_rejected_to_exploitation_motif' => 'text',

                // RENG rejette vers RJU
                'engagements_rejected_to_juridique_at' => 'timestamp',
                'engagements_rejected_to_juridique_by_user_id' => 'unsignedBigInteger',
                'engagements_rejected_to_juridique_motif' => 'text',

                // RISQ rejette vers RENG
                'risques_rejected_to_engagements_at' => 'timestamp',
                'risques_rejected_to_engagements_by_user_id' => 'unsignedBigInteger',
                'risques_rejected_to_engagements_motif' => 'text',

                // Direction (DG / DGA / délégué) rejette vers RISQ (sans clôturer)
                'direction_rejected_to_risques_at' => 'timestamp',
                'direction_rejected_to_risques_by_user_id' => 'unsignedBigInteger',
                'direction_rejected_to_risques_motif' => 'text',
            ];

            foreach ($columns as $name => $type) {
                if (Schema::connection($c)->hasColumn('dossiers', $name)) {
                    continue;
                }
                if ($type === 'timestamp') {
                    $table->timestamp($name)->nullable();
                } elseif ($type === 'unsignedBigInteger') {
                    $table->unsignedBigInteger($name)->nullable();
                } else {
                    $table->text($name)->nullable();
                }
            }
        });
    }

    public function down(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            foreach ([
                'juridique_rejected_to_exploitation_at',
                'juridique_rejected_to_exploitation_by_user_id',
                'juridique_rejected_to_exploitation_motif',
                'engagements_rejected_to_juridique_at',
                'engagements_rejected_to_juridique_by_user_id',
                'engagements_rejected_to_juridique_motif',
                'risques_rejected_to_engagements_at',
                'risques_rejected_to_engagements_by_user_id',
                'risques_rejected_to_engagements_motif',
                'direction_rejected_to_risques_at',
                'direction_rejected_to_risques_by_user_id',
                'direction_rejected_to_risques_motif',
            ] as $col) {
                if (Schema::connection($c)->hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
