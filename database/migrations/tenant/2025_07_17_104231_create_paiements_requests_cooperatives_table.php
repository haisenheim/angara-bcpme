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
        Schema::create('paiements_requests_cooperatives', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('operateur_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->integer('wallet_id')->default(0);
            $table->integer('caisse_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->double('montant', null, 0)->default(0);
            $table->tinyInteger('active')->default(1);
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('validated_at')->nullable();
            $table->integer('validated_by')->default(0);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->text('payload')->nullable();
            $table->string('token', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements_requests_cooperatives');
    }
};
