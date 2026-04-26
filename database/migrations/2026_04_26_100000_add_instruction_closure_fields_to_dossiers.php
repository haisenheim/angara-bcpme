<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var string */
    protected $connection = 'central_app_mysql';

    public function up(): void
    {
        $c = $this->connection;

        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_validated_at')) {
                $table->timestamp('instruction_closure_validated_at')->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_validated_by_user_id')) {
                $table->unsignedBigInteger('instruction_closure_validated_by_user_id')->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_rejected_at')) {
                $table->timestamp('instruction_closure_rejected_at')->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_rejected_by_user_id')) {
                $table->unsignedBigInteger('instruction_closure_rejected_by_user_id')->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_reject_motif')) {
                $table->text('instruction_closure_reject_motif')->nullable();
            }
            if (! Schema::connection($c)->hasColumn('dossiers', 'instruction_closure_note')) {
                $table->text('instruction_closure_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        $c = $this->connection;
        Schema::connection($c)->table('dossiers', function (Blueprint $table) use ($c) {
            foreach ([
                'instruction_closure_validated_at',
                'instruction_closure_validated_by_user_id',
                'instruction_closure_rejected_at',
                'instruction_closure_rejected_by_user_id',
                'instruction_closure_reject_motif',
                'instruction_closure_note',
            ] as $col) {
                if (Schema::connection($c)->hasColumn('dossiers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

