<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisation_entites', function (Blueprint $table) {
            $table->id();
            $table->string('key', 60)->unique();
            $table->string('name', 150);
            $table->string('type', 40); // pole, direction_generale, conseil_administration, audit_ci, etc.
            $table->string('route_prefix', 40)->nullable(); // ex: respexp, juridique, reng, rerx, dg, dga, pca, administrateur...
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_entites');
    }
};

