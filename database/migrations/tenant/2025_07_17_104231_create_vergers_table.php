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
        Schema::create('vergers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->string('localisation', 100)->nullable();
            $table->string('latitude', 50)->nullable();
            $table->string('longitude', 50)->nullable();
            $table->integer('annee')->nullable();
            $table->integer('type_id')->default(0);
            $table->string('photo_uri', 100)->nullable();
            $table->integer('village_id')->default(0);
            $table->integer('arrondissement_id')->default(0);
            $table->integer('departement_id')->default(0);
            $table->integer('region_id')->default(0);
            $table->integer('membre_id')->default(0);
            $table->double('size', null, 0)->default(0);
            $table->integer('nbph')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('user_id')->default(0);
            $table->boolean('active')->default(false);
            $table->string('token', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vergers');
    }
};
