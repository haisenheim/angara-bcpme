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
        Schema::create('operateur_mobiles_agents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('cooperative_id')->default(0);
            $table->integer('operateur_id')->default(0);
            $table->integer('agent_id')->default(0);
            $table->string('phone', 15)->nullable();
            $table->double('montant', null, 0)->default(0);
            $table->integer('user_id')->default(0);
            $table->boolean('active')->default(true);
            $table->string('token', 100)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operateur_mobiles_agents');
    }
};
