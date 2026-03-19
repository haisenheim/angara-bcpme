<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossier_esg_evaluation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_esg_evaluation_id')->constrained('dossier_esg_evaluations')->cascadeOnDelete();
            $table->string('category_code');
            $table->string('indicator_code');
            $table->string('indicator_label')->nullable();
            $table->text('indicator_description')->nullable();
            $table->string('input_type')->nullable();
            $table->text('value_text')->nullable();
            $table->decimal('value_number', 15, 2)->nullable();
            $table->boolean('value_boolean')->nullable();
            $table->decimal('score', 10, 2)->default(0);
            $table->decimal('weight', 10, 2)->default(1);
            $table->text('comment')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_esg_evaluation_items');
    }
};
