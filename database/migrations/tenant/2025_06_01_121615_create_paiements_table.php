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
        Schema::create('paiements', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 20)->nullable();
            $table->integer('entree_id')->default(0);
            $table->integer('agent_id')->default(0);
            $table->integer('exploitant_id')->default(0);
            $table->integer('mode_paiement_id')->default(0);
            $table->integer('agent_wallet_id')->default(0);
            $table->integer('caisse_id')->default(0);
            $table->integer('wallet_id')->default(0);
            //$table->integer('cooperative_id')->default(0);
            $table->double('montant', null, 0)->default(0);
            $table->string('phone', 30)->nullable();
            $table->boolean('active')->default(true);
            $table->integer('user_id')->default(0);
            $table->text('token')->nullable();
            $table->integer('saison_id')->default(0);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
