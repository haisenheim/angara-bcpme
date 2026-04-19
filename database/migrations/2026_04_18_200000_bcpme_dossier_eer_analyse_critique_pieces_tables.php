<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Deux processus BC-PME :
 * - entrée en relation (prospect -> client -> qualification -> soumission -> validation chef d'agence)
 * - instruction des engagements (dossiers existants, par programme)
 */
return new class extends Migration
{
    protected string $c = 'central_app_mysql';

    public function up(): void
    {
        if (! Schema::connection($this->c)->hasTable('piece_exigible_definitions')) {
            Schema::connection($this->c)->create('piece_exigible_definitions', function (Blueprint $table) {
                $table->id();
                $table->string('label');
                $table->text('description')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::connection($this->c)->hasTable('entreprise_piece_exigibles')) {
            Schema::connection($this->c)->create('entreprise_piece_exigibles', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('entreprise_id');
                $table->unsignedBigInteger('piece_exigible_definition_id');
                $table->integer('fichier_id')->nullable();
                $table->timestamp('provided_at')->nullable();
                $table->unsignedBigInteger('provided_by_user_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['entreprise_id', 'piece_exigible_definition_id'], 'entreprise_piece_unique');
                $table->index('entreprise_id');

                $table->foreign('entreprise_id', 'ent_piece_entreprise_fk')->references('id')->on('entreprises')->cascadeOnDelete();
                $table->foreign('piece_exigible_definition_id', 'ent_piece_definition_fk')->references('id')->on('piece_exigible_definitions')->cascadeOnDelete();
                $table->foreign('fichier_id', 'ent_piece_fichier_fk')->references('id')->on('fichiers')->nullOnDelete();
                $table->foreign('provided_by_user_id', 'ent_piece_user_fk')->references('id')->on('users')->nullOnDelete();
            });
        }

        if (! Schema::connection($this->c)->hasTable('dossier_entree_relations')) {
            Schema::connection($this->c)->create('dossier_entree_relations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('entreprise_id')->unique();
                $table->string('token', 100)->nullable()->unique();
                $table->string('statut', 32)->default('brouillon');
                $table->timestamp('submitted_at')->nullable();
                $table->unsignedBigInteger('submitted_by_user_id')->nullable();
                $table->longText('analyse_strategique')->nullable();
                $table->longText('analyse_operationnelle')->nullable();
                $table->longText('analyse_eligibilite')->nullable();
                $table->longText('identification_besoins')->nullable();
                $table->boolean('besoin_financement')->default(false);
                $table->boolean('besoin_accompagnement')->default(false);
                $table->boolean('besoin_structuration')->default(false);
                $table->longText('qualification_notes')->nullable();
                $table->timestamp('qualification_completed_at')->nullable();
                $table->unsignedBigInteger('qualification_user_id')->nullable();
                $table->timestamp('programmes_submitted_at')->nullable();
                $table->unsignedBigInteger('programmes_submitted_by_user_id')->nullable();
                $table->string('instruction_validation_status', 32)->default('brouillon');
                $table->timestamp('instruction_validated_at')->nullable();
                $table->unsignedBigInteger('instruction_validated_by_user_id')->nullable();
                $table->timestamps();

                $table->foreign('entreprise_id', 'eer_entreprise_fk')->references('id')->on('entreprises')->cascadeOnDelete();
                $table->foreign('submitted_by_user_id', 'eer_submitted_user_fk')->references('id')->on('users')->nullOnDelete();
                $table->foreign('qualification_user_id', 'eer_qualification_user_fk')->references('id')->on('users')->nullOnDelete();
                $table->foreign('programmes_submitted_by_user_id', 'eer_programmes_user_fk')->references('id')->on('users')->nullOnDelete();
                $table->foreign('instruction_validated_by_user_id', 'eer_instruction_user_fk')->references('id')->on('users')->nullOnDelete();
            });
        }

        if (! Schema::connection($this->c)->hasTable('dossier_entree_relation_programmes')) {
            Schema::connection($this->c)->create('dossier_entree_relation_programmes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dossier_entree_relation_id');
                $table->integer('programme_id');
                $table->string('type_appui', 32)->default('financier');
                $table->string('statut', 32)->default('propose');
                $table->text('notes')->nullable();
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('validated_at')->nullable();
                $table->unsignedInteger('instruction_dossier_id')->nullable();
                $table->timestamps();

                $table->unique(['dossier_entree_relation_id', 'programme_id'], 'eer_programme_unique');
                $table->foreign('dossier_entree_relation_id', 'eer_prog_eer_fk')->references('id')->on('dossier_entree_relations')->cascadeOnDelete();
                $table->foreign('programme_id', 'eer_prog_programme_fk')->references('id')->on('programmes')->restrictOnDelete();
                $table->foreign('instruction_dossier_id', 'eer_prog_dossier_fk')->references('id')->on('dossiers')->nullOnDelete();
            });
        }

        if (! Schema::connection($this->c)->hasTable('dossier_analyse_critiques')) {
            Schema::connection($this->c)->create('dossier_analyse_critiques', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('entreprise_id')->unique();
                $table->string('token', 100)->nullable()->unique();
                $table->longText('synthese')->nullable();
                $table->string('statut', 32)->default('brouillon');
                $table->timestamps();

                $table->foreign('entreprise_id', 'dac_entreprise_fk')->references('id')->on('entreprises')->cascadeOnDelete();
            });
        }

        if (! Schema::connection($this->c)->hasTable('analyse_critique_avis')) {
            Schema::connection($this->c)->create('analyse_critique_avis', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dossier_analyse_critique_id');
                $table->string('source_type', 64);
                $table->string('source_label')->nullable();
                $table->unsignedInteger('instruction_dossier_id')->nullable();
                $table->text('contenu')->nullable();
                $table->string('etat', 32)->default('emis');
                $table->timestamp('emis_at')->nullable();
                $table->unsignedBigInteger('emis_par_user_id')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();

                $table->index(['dossier_analyse_critique_id', 'sort_order'], 'aca_dac_sort_idx');
                $table->index('instruction_dossier_id', 'aca_instruction_idx');

                $table->foreign('dossier_analyse_critique_id', 'aca_dac_fk')->references('id')->on('dossier_analyse_critiques')->cascadeOnDelete();
                $table->foreign('instruction_dossier_id', 'aca_dossier_fk')->references('id')->on('dossiers')->nullOnDelete();
                $table->foreign('emis_par_user_id', 'aca_user_fk')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::connection($this->c)->dropIfExists('analyse_critique_avis');
        Schema::connection($this->c)->dropIfExists('dossier_analyse_critiques');
        Schema::connection($this->c)->dropIfExists('dossier_entree_relation_programmes');
        Schema::connection($this->c)->dropIfExists('dossier_entree_relations');
        Schema::connection($this->c)->dropIfExists('entreprise_piece_exigibles');
        Schema::connection($this->c)->dropIfExists('piece_exigible_definitions');
    }
};
