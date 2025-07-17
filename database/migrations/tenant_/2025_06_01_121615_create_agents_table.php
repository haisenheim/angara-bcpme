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
        Schema::create('agents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('password')->nullable();
            $table->integer('cooperative_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->integer('region_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('arrondissement_id')->default(0);
            $table->string('photo_uri', 100)->nullable();
            $table->string('token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
