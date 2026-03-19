<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->nullable()->constrained('evaluation_frameworks')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('evaluation_categories')->nullOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('input_type')->nullable();
            $table->string('score_type')->nullable();
            $table->decimal('default_weight', 10, 2)->default(1);
            $table->decimal('min_score', 10, 2)->default(0);
            $table->decimal('max_score', 10, 2)->default(100);
            $table->boolean('required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_indicators');
    }
};
