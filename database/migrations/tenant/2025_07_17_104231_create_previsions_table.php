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
        Schema::create('previsions', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100)->nullable();
            $table->integer('agent_id')->default(0);
            $table->integer('membre_id')->default(0);
            $table->integer('gamme_id')->default(0);
            $table->integer('entrepot_id')->default(0);
            $table->integer('agence_id')->default(0);
            $table->integer('representation_id')->default(0);
            $table->integer('quantity')->default(0);
            $table->date('day')->nullable();
            $table->double('pu', null, 0)->default(0);
            $table->double('montant', null, 0)->nullable()->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->dateTime('validated_at')->nullable();
            $table->integer('validated_by')->default(0);
            $table->dateTime('cancelled_at')->nullable();
            $table->integer('cancelled_by')->default(0);
            $table->string('token', 100)->nullable();
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
        Schema::dropIfExists('previsions');
    }
};
