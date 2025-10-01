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
        Schema::create('campagnes', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('verger_id')->default(0);
            $table->integer('saison_id')->default(0);
            $table->integer('membre_id')->default(0);
            $table->integer('morts')->default(0);
            $table->integer('replantations')->default(0);
            $table->text('etat')->nullable();
            $table->text('observations')->nullable();
            $table->double('ombrage', null, 0)->default(0);
            $table->double('rendement', null, 0)->nullable()->default(0);
            $table->boolean('active')->default(true);
            $table->integer('user_id')->default(0);
            $table->integer('user_group_id')->default(0);
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
        Schema::dropIfExists('campagnes');
    }
};
