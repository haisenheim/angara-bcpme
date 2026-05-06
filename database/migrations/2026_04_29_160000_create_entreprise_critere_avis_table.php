<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('central_app_mysql')->hasTable('entreprise_critere_avis')) {
            return;
        }

        Schema::connection('central_app_mysql')->create('entreprise_critere_avis', function (Blueprint $table) {
            $table->bigIncrements('id');

            // entreprises.id est un INT UNSIGNED dans le dump historique
            $table->unsignedInteger('entreprise_id')->index();

            // référentiel des critères (questionnaire mise en relation)
            $table->unsignedBigInteger('critere_id')->index();

            // auteur (gestionnaire) du dernier enregistrement
            $table->unsignedBigInteger('user_id')->nullable()->index();

            $table->longText('avis')->nullable();
            $table->dateTime('saved_at')->nullable()->index();

            $table->timestamps();

            $table->unique(['entreprise_id', 'critere_id'], 'uniq_entreprise_critere_avis');

            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprise_critere_avis')) {
            return;
        }

        Schema::connection('central_app_mysql')->dropIfExists('entreprise_critere_avis');
    }
};

