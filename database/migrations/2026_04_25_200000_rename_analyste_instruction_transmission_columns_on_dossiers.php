<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('dossiers', 'exploitation_instruction_submitted_at')
            && ! Schema::hasColumn('dossiers', 'exploitation_analyste_transmitted_to_exploitation_at')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $table->renameColumn(
                    'exploitation_instruction_submitted_at',
                    'exploitation_analyste_transmitted_to_exploitation_at'
                );
            });
        }

        if (Schema::hasColumn('dossiers', 'exploitation_instruction_submitted_by_user_id')
            && ! Schema::hasColumn('dossiers', 'exploitation_analyste_transmitted_to_exploitation_by_user_id')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $table->renameColumn(
                    'exploitation_instruction_submitted_by_user_id',
                    'exploitation_analyste_transmitted_to_exploitation_by_user_id'
                );
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dossiers', 'exploitation_analyste_transmitted_to_exploitation_at')
            && ! Schema::hasColumn('dossiers', 'exploitation_instruction_submitted_at')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $table->renameColumn(
                    'exploitation_analyste_transmitted_to_exploitation_at',
                    'exploitation_instruction_submitted_at'
                );
            });
        }

        if (Schema::hasColumn('dossiers', 'exploitation_analyste_transmitted_to_exploitation_by_user_id')
            && ! Schema::hasColumn('dossiers', 'exploitation_instruction_submitted_by_user_id')) {
            Schema::table('dossiers', function (Blueprint $table) {
                $table->renameColumn(
                    'exploitation_analyste_transmitted_to_exploitation_by_user_id',
                    'exploitation_instruction_submitted_by_user_id'
                );
            });
        }
    }
};
