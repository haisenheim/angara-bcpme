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
        Schema::create('sorties', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 20)->nullable();
            $table->integer('cooperative_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->double('quantity', null, 0)->default(0);
            $table->boolean('inside')->default(true);
            $table->integer('domaine_id')->default(0);
            $table->integer('gamme_id')->default(0);
            $table->double('pu', null, 0)->default(0);
            $table->double('montant', null, 0)->default(0);
            $table->integer('entrepot_source_id')->default(0);
            $table->integer('entrepot_target_id')->default(0);
            $table->integer('offtaker_id')->default(0);
            $table->boolean('active')->default(true);
            $table->integer('user_id')->default(0);
            $table->dateTime('paid_at')->nullable();
            $table->integer('paid_by')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('token', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sorties');
    }
};
