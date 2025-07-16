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
        Schema::create('entrepots_gammes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('entrepot_id')->default(0);
            $table->integer('gamme_id')->default(0);
            $table->double('quantity', null, 0)->default(0);
           // $table->integer('cooperative_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrepots_gammes');
    }
};
