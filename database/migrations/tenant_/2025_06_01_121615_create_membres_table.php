<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('membres', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->integer('village_id')->default(1);
            $table->string('phone')->nullable();
            $table->string('email', 100)->nullable();
            $table->boolean('male')->default(true);
            $table->date('dtn')->nullable();
            $table->string('lieu')->nullable();
            $table->integer('niveau_id')->default(0);
            $table->string('cni', 30)->nullable();
            $table->date('dt_expiration_cni')->nullable();
            //$table->integer('cooperative_id')->default(0);
            $table->integer('entreprise_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->date('dt_adhesion')->nullable();
            $table->string('compte_bancaire')->nullable();
            $table->enum('situation_matrimoniale', ['Celibataire', 'Marie(e)', 'Veuf/Veuve'])->nullable();
            $table->integer('nb_enfants')->default(0);
            $table->boolean('active')->default(true);
            $table->string('photo_uri')->nullable();
            $table->timestamps();
            $table->integer('region_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('arrondissement_id')->default(0);
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exploitants');
    }
};
