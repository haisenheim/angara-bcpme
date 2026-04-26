<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_rejected_at')) {
                $table->timestamp('instruction_bundle_rejected_at')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_rejected_by_user_id')) {
                $table->unsignedBigInteger('instruction_bundle_rejected_by_user_id')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_reject_motif')) {
                $table->text('instruction_bundle_reject_motif')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_reject_motif')) {
                $table->dropColumn('instruction_bundle_reject_motif');
            }
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_rejected_by_user_id')) {
                $table->dropColumn('instruction_bundle_rejected_by_user_id');
            }
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_rejected_at')) {
                $table->dropColumn('instruction_bundle_rejected_at');
            }
        });
    }
};
