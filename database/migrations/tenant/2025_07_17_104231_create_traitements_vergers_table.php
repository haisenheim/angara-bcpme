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
        Schema::create('traitements_vergers', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 192)->nullable();
            $table->integer('campagne_id')->default(0);
            $table->integer('verger_id')->default(0);
            $table->integer('membre_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->date('jour')->nullable();
            $table->text('dosage')->nullable();
            $table->text('description')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('produit_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traitements_vergers');
    }
};
