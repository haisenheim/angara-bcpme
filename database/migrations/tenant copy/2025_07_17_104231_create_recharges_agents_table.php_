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
        Schema::create('recharges_agents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('type_id')->default(0);
            $table->integer('agent_id')->default(0);
            $table->integer('wallet_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
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
        Schema::dropIfExists('recharges_agents');
    }
};
