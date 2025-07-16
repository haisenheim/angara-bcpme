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
        Schema::create('entrepots', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 60)->nullable();
           // $table->integer('cooperative_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->integer('client_id')->default(0);
            $table->string('latitude', 30)->nullable();
            $table->string('longitude', 30)->nullable();
            $table->integer('arrondissement_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('region_id')->default(0);
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('entrepots');
    }
};
