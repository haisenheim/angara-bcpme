<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Dossiers multi-programmes : les programmes sont portés par dossier_instruction_programmes,
 * plus de programme_id « principal » sur dossiers.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('dossier_instruction_programmes') || ! Schema::hasTable('dossiers')) {
            return;
        }

        DB::table('dossiers')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('dossier_instruction_programmes')
                    ->whereColumn('dossier_instruction_programmes.dossier_id', 'dossiers.id');
            })
            ->update(['programme_id' => null]);
    }

    public function down(): void
    {
        // Non réversible sans sauvegarde du programme_id historique.
    }
};
