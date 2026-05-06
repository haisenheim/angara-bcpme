<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mécanisme de « rejet intermédiaire » par les responsables de pôle (prompt l. 220).
 *
 * Pour chaque pôle de Workflow 2 (Exploitation, Juridique, Engagements, Risques), le responsable
 * peut désormais rejeter le travail de son analyste : l'analyste est alors à nouveau libre de
 * modifier son contenu et de retransmettre. Les colonnes de rejet conservent l'horodatage,
 * l'identité de l'auteur et un motif obligatoire.
 *
 * Conventions :
 * - <pole>_analyste_rejected_at : horodatage du rejet
 * - <pole>_analyste_rejected_by_user_id : auteur du rejet (responsable)
 * - <pole>_analyste_reject_motif : motif (texte) saisi par le responsable
 *
 * Verrouillage métier : un avis analyste est figé si « submitted_at IS NOT NULL AND rejected_at IS NULL ».
 * Le rejet remet le dossier dans un état modifiable côté analyste, sans effacer les soumissions
 * antérieures (préservation de l'historique).
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
                // Pôle Exploitation : analyste financier (AF) → REXP
                'exploitation_analyste_rejected_at' => 'timestamp',
                'exploitation_analyste_rejected_by_user_id' => 'unsignedBigInteger',
                'exploitation_analyste_reject_motif' => 'text',

                // Pôle Juridique : analyste juridique (AJ) → RJU
                'juridique_analyste_rejected_at' => 'timestamp',
                'juridique_analyste_rejected_by_user_id' => 'unsignedBigInteger',
                'juridique_analyste_reject_motif' => 'text',

                // Pôle Engagements : analyste crédit (AC) → RENG
                'reng_analyste_credit_rejected_at' => 'timestamp',
                'reng_analyste_credit_rejected_by_user_id' => 'unsignedBigInteger',
                'reng_analyste_credit_reject_motif' => 'text',

                // Pôle Risques : analyste risques (AR) → RISQ
                'rerx_analyste_risques_rejected_at' => 'timestamp',
                'rerx_analyste_risques_rejected_by_user_id' => 'unsignedBigInteger',
                'rerx_analyste_risques_reject_motif' => 'text',
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
                'exploitation_analyste_rejected_at',
                'exploitation_analyste_rejected_by_user_id',
                'exploitation_analyste_reject_motif',
                'juridique_analyste_rejected_at',
                'juridique_analyste_rejected_by_user_id',
                'juridique_analyste_reject_motif',
                'reng_analyste_credit_rejected_at',
                'reng_analyste_credit_rejected_by_user_id',
                'reng_analyste_credit_reject_motif',
                'rerx_analyste_risques_rejected_at',
                'rerx_analyste_risques_rejected_by_user_id',
                'rerx_analyste_risques_reject_motif',
            ] as $col) {
                if (Schema::connection($c)->hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
