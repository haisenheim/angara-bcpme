<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->c)->hasTable('dossier_entree_relations')) {
            return;
        }

        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_rejected_by_agence_at')) {
                $table->timestamp('qualification_rejected_by_agence_at')->nullable()->after('qualification_validated_by_agence_user_id');
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_rejected_by_agence_user_id')) {
                $table->unsignedBigInteger('qualification_rejected_by_agence_user_id')->nullable()->after('qualification_rejected_by_agence_at');
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_reject_motif')) {
                $table->text('qualification_reject_motif')->nullable()->after('qualification_rejected_by_agence_user_id');
            }
        });

        if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_rejected_by_agence_user_id')) {
            Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
                $table->foreign('qualification_rejected_by_agence_user_id', 'eer_qualif_reject_agence_user_fk')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::connection($this->c)->hasTable('dossier_entree_relations')) {
            return;
        }

        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_rejected_by_agence_user_id')) {
                $table->dropForeign('eer_qualif_reject_agence_user_fk');
            }
        });

        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            foreach (['qualification_reject_motif', 'qualification_rejected_by_agence_user_id', 'qualification_rejected_by_agence_at'] as $col) {
                if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
