<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Décision chef d’agence par dossier d’instruction (multi-dossiers par client).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            if (! Schema::hasColumn('dossiers', 'instruction_agence_validated_at')) {
                $table->timestamp('instruction_agence_validated_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_validated_by_user_id')) {
                $table->unsignedBigInteger('instruction_agence_validated_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_rejected_at')) {
                $table->timestamp('instruction_agence_rejected_at')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_rejected_by_user_id')) {
                $table->unsignedBigInteger('instruction_agence_rejected_by_user_id')->nullable();
            }
            if (! Schema::hasColumn('dossiers', 'instruction_agence_reject_motif')) {
                $table->text('instruction_agence_reject_motif')->nullable();
            }
        });

        if (! Schema::hasTable('dossier_entree_relations')) {
            return;
        }

        $eers = DB::table('dossier_entree_relations')
            ->whereNotNull('instruction_bundle_dossier_id')
            ->get(['instruction_bundle_dossier_id', 'instruction_bundle_submitted_at', 'instruction_bundle_submitted_by_user_id', 'instruction_bundle_validated_at', 'instruction_bundle_validated_by_user_id', 'instruction_bundle_rejected_at', 'instruction_bundle_rejected_by_user_id', 'instruction_bundle_reject_motif']);

        foreach ($eers as $eer) {
            $did = (int) $eer->instruction_bundle_dossier_id;
            if ($did < 1) {
                continue;
            }

            $updates = [];
            if ($eer->instruction_bundle_submitted_at) {
                $updates['chef_filiere_submitted_to_agence_at'] = $eer->instruction_bundle_submitted_at;
                $updates['chef_filiere_submitted_to_agence_by_user_id'] = $eer->instruction_bundle_submitted_by_user_id;
            }
            if ($eer->instruction_bundle_validated_at) {
                $updates['instruction_agence_validated_at'] = $eer->instruction_bundle_validated_at;
                $updates['instruction_agence_validated_by_user_id'] = $eer->instruction_bundle_validated_by_user_id;
            }
            if ($eer->instruction_bundle_rejected_at) {
                $updates['instruction_agence_rejected_at'] = $eer->instruction_bundle_rejected_at;
                $updates['instruction_agence_rejected_by_user_id'] = $eer->instruction_bundle_rejected_by_user_id;
                $updates['instruction_agence_reject_motif'] = $eer->instruction_bundle_reject_motif;
            }

            if ($updates !== []) {
                DB::table('dossiers')->where('id', $did)->update($updates);
            }
        }
    }

    public function down(): void
    {
        Schema::table('dossiers', function (Blueprint $table) {
            foreach ([
                'instruction_agence_reject_motif',
                'instruction_agence_rejected_by_user_id',
                'instruction_agence_rejected_at',
                'instruction_agence_validated_by_user_id',
                'instruction_agence_validated_at',
            ] as $col) {
                if (Schema::hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
