<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigInteger('id')->autoIncrement();

            // your custom columns may go here
            $table->string('name', 150)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('phone', 25)->nullable();
            $table->integer('region_id')->default(0);
            $table->integer('entreprise_id')->default(0);
            $table->integer('domaine_id')->default(0);
            $table->integer('secteur_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->integer('arrondissement_id')->default(0);
            $table->boolean('active')->default(true);
            $table->string('token')->nullable();
            $table->string('photo_uri', 100)->nullable();
            $table->integer('departement_id')->default(0);

            $table->timestamps();
            $table->json('data')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
}
