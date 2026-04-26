<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->c)->hasTable('dossier_instruction_programmes')) {
            Schema::connection($this->c)->create('dossier_instruction_programmes', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('dossier_id');
                $table->integer('programme_id');
                $table->decimal('budget_appui_financier', 15, 2)->default(0);
                $table->decimal('budget_appui_non_financier', 15, 2)->default(0);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->unique(['dossier_id', 'programme_id'], 'dip_dossier_programme_unique');
                $table->foreign('dossier_id', 'dip_dossier_fk')->references('id')->on('dossiers')->cascadeOnDelete();
                $table->foreign('programme_id', 'dip_programme_fk')->references('id')->on('programmes')->restrictOnDelete();
            });
        }

        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_submitted_at')) {
                $table->timestamp('instruction_bundle_submitted_at')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_submitted_by_user_id')) {
                $table->unsignedBigInteger('instruction_bundle_submitted_by_user_id')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_validated_at')) {
                $table->timestamp('instruction_bundle_validated_at')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_validated_by_user_id')) {
                $table->unsignedBigInteger('instruction_bundle_validated_by_user_id')->nullable();
            }
            if (! Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_dossier_id')) {
                $table->unsignedInteger('instruction_bundle_dossier_id')->nullable();
                $table->foreign('instruction_bundle_dossier_id', 'eer_instruction_bundle_dossier_fk')
                    ->references('id')
                    ->on('dossiers')
                    ->nullOnDelete();
            }
        });

        if (Schema::connection($this->c)->hasColumn('dossiers', 'programme_id')) {
            DB::connection($this->c)->statement('ALTER TABLE dossiers MODIFY programme_id INT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        Schema::connection($this->c)->table('dossier_entree_relations', function (Blueprint $table) {
            if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', 'instruction_bundle_dossier_id')) {
                $table->dropForeign('eer_instruction_bundle_dossier_fk');
                $table->dropColumn('instruction_bundle_dossier_id');
            }
            foreach ([
                'instruction_bundle_validated_by_user_id',
                'instruction_bundle_validated_at',
                'instruction_bundle_submitted_by_user_id',
                'instruction_bundle_submitted_at',
            ] as $col) {
                if (Schema::connection($this->c)->hasColumn('dossier_entree_relations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });

        if (Schema::connection($this->c)->hasTable('dossier_instruction_programmes')) {
            Schema::connection($this->c)->dropIfExists('dossier_instruction_programmes');
        }
    }
};
