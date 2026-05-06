<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('central_app_mysql')->hasTable('entreprise_sites')) {
            return;
        }

        Schema::connection('central_app_mysql')->create('entreprise_sites', function (Blueprint $table) {
            $table->bigIncrements('id');

            // entreprises.id est un INT UNSIGNED dans le dump historique
            $table->unsignedInteger('entreprise_id')->index();

            $table->string('libelle', 255);

            $table->unsignedBigInteger('region_id')->nullable()->index();
            $table->unsignedBigInteger('departement_id')->nullable()->index();
            $table->unsignedBigInteger('arrondissement_id')->nullable()->index(); // commune

            $table->unsignedBigInteger('village_id')->nullable()->index();
            $table->unsignedBigInteger('quartier_id')->nullable()->index();
            $table->string('village_ou_quartier', 255)->nullable();

            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();

            $table->string('telephone', 50)->nullable();
            $table->string('email', 100)->nullable();

            $table->longText('divers')->nullable();

            $table->unsignedBigInteger('created_by_user_id')->nullable()->index();
            $table->unsignedBigInteger('updated_by_user_id')->nullable()->index();

            $table->timestamps();

            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        if (! Schema::connection('central_app_mysql')->hasTable('entreprise_sites')) {
            return;
        }

        Schema::connection('central_app_mysql')->dropIfExists('entreprise_sites');
    }
};

