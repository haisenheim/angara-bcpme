<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dossier_esg_evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_id')->unique();
            $table->unsignedInteger('entreprise_id');
            $table->unsignedInteger('programme_id')->nullable();
            $table->unsignedBigInteger('agence_id')->nullable();
            $table->unsignedBigInteger('gestionnaire_id')->nullable();
            $table->unsignedBigInteger('analyste_id')->nullable();

            // Cadre d'évaluation
            $table->string('reference_framework')->nullable();
            $table->string('evaluation_type')->nullable();
            $table->string('status', 20)->default('draft'); // draft, submitted, validated, rejected

            // Dates
            $table->date('evaluation_date')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // Scores
            $table->decimal('score_environmental', 10, 2)->default(0);
            $table->decimal('score_social', 10, 2)->default(0);
            $table->decimal('score_governance', 10, 2)->default(0);
            $table->decimal('score_financial', 10, 2)->default(0);
            $table->decimal('score_compliance', 10, 2)->default(0);
            $table->decimal('score_global', 10, 2)->default(0);

            // Résultats analytiques
            $table->string('risk_level')->nullable();
            $table->string('bankability_level')->nullable();
            $table->boolean('eligibility_blending')->default(false);
            $table->boolean('eligibility_guarantee')->default(false);
            $table->boolean('eligibility_global_gateway')->default(false);
            $table->boolean('exclusion_flag')->default(false);
            $table->boolean('minimum_compliance_passed')->default(false);
            $table->json('sdg_alignment')->nullable();

            // Appréciation qualitative
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('due_diligence_notes')->nullable();
            $table->text('analyst_conclusion')->nullable();
            $table->text('validation_comment')->nullable();
            $table->text('rejection_reason')->nullable();

            // Traçabilité
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->unsignedBigInteger('rejected_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dossier_esg_evaluations');
    }
};
