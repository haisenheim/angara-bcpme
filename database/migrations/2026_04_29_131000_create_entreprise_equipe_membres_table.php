<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('central_app_mysql')->hasTable('entreprise_equipe_membres')) {
            return;
        }

        Schema::connection('central_app_mysql')->create('entreprise_equipe_membres', function (Blueprint $table) {
            $table->bigIncrements('id');

            // entreprises.id est un INT UNSIGNED dans le dump historique
            $table->unsignedInteger('entreprise_id')->index();
            $table->unsignedBigInteger('entreprise_site_id')->nullable()->index();

            $table->string('nom', 255);
            $table->string('prenom', 255)->nullable();
            $table->string('telephone', 50);
            $table->string('email', 100)->nullable();

            $table->date('date_naissance')->nullable();
            $table->string('fonction', 255)->nullable();
            $table->string('niveau_etude', 80)->nullable();
            $table->string('specialite', 255)->nullable();

            $table->boolean('associe')->default(false);

            $table->longText('divers')->nullable();

            $table->unsignedBigInteger('created_by_user_id')->nullable()->index();
            $table->unsignedBigInteger('updated_by_user_id')->nullable()->index();

            $table->timestamps();

            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('entreprise_site_id')->references('id')->on('entreprise_sites')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprise_equipe_membres')) {
            return;
        }

        Schema::connection('central_app_mysql')->dropIfExists('entreprise_equipe_membres');
    }
};

