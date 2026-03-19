<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_score_thresholds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('threshold_type'); // risk, bankability, eligibility
            $table->string('label');
            $table->decimal('min_value', 10, 2)->default(0);
            $table->decimal('max_value', 10, 2)->default(100);
            $table->string('color')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_score_thresholds');
    }
};
