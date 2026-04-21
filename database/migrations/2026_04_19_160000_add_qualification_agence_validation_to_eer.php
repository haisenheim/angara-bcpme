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
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_at')) {
                $table->timestamp('qualification_validated_by_agence_at')->nullable()->after('instruction_validated_by_user_id');
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_user_id')) {
                $table->unsignedBigInteger('qualification_validated_by_agence_user_id')->nullable()->after('qualification_validated_by_agence_at');
            }
        });

        if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_user_id')) {
            Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
                $table->foreign('qualification_validated_by_agence_user_id', 'eer_qualif_agence_user_fk')
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
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_user_id')) {
                $table->dropForeign('eer_qualif_agence_user_fk');
            }
        });

        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            $drops = [];
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_at')) {
                $drops[] = 'qualification_validated_by_agence_at';
            }
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'qualification_validated_by_agence_user_id')) {
                $drops[] = 'qualification_validated_by_agence_user_id';
            }
            if ($drops !== []) {
                $table->dropColumn($drops);
            }
        });
    }
};
