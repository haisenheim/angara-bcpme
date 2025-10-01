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
        Schema::create('membres_plateformes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('exploitant_id')->default(0);
            $table->integer('plateforme_id')->default(0);
            $table->string('user_key', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membres_plateformes');
    }
};
