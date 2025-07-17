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
        Schema::create('calendrier_items', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('calendrier_id')->default(0);
            $table->integer('cooperative_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->date('day')->nullable();
            $table->string('lieu', 50)->nullable();
            $table->integer('arrondissement_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('region_id')->default(0);
            $table->tinyInteger('active')->default(1);
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
        Schema::dropIfExists('calendrier_items');
    }
};
