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
        Schema::create('calendriers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('token', 100)->nullable();
            $table->integer('cooperative_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->integer('region_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('arrondissement_id')->default(0);
            $table->integer('protocole_id')->default(0);
            $table->boolean('active')->default(true);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendriers');
    }
};
