<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entreprise_evaluation_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('entreprise_id')->unique();
            $table->unsignedBigInteger('gestionnaire_id')->nullable();
            $table->unsignedBigInteger('agence_id')->nullable();

            // Informations générales
            $table->string('reference_framework')->nullable();
            $table->string('business_sector')->nullable();
            $table->string('business_subsector')->nullable();
            $table->decimal('annual_turnover', 15, 2)->nullable();
            $table->integer('annual_turnover_year')->nullable();
            $table->decimal('net_income', 15, 2)->nullable();
            $table->decimal('balance_sheet_total', 15, 2)->nullable();
            $table->integer('employees_total')->default(0);
            $table->integer('employees_permanent')->default(0);
            $table->integer('employees_temporary')->default(0);

            // Bloc environnement
            $table->boolean('env_policy')->default(false);
            $table->boolean('env_certified')->default(false);
            $table->string('env_certification_type')->nullable();
            $table->boolean('uses_renewable_energy')->default(false);
            $table->decimal('renewable_energy_ratio', 5, 2)->default(0);
            $table->boolean('waste_management_system')->default(false);
            $table->boolean('water_management_system')->default(false);
            $table->boolean('carbon_measurement')->default(false);
            $table->decimal('estimated_co2_emission', 15, 2)->default(0);
            $table->string('climate_risk_exposure')->nullable();
            $table->boolean('climate_adaptation_strategy')->default(false);

            // Bloc social
            $table->boolean('women_led')->default(false);
            $table->boolean('youth_led')->default(false);
            $table->integer('nb_women')->default(0);
            $table->integer('nb_youth')->default(0);
            $table->integer('nb_disabled')->default(0);
            $table->boolean('inclusive_business')->default(false);
            $table->boolean('social_protection')->default(false);
            $table->boolean('training_program')->default(false);
            $table->boolean('community_impact')->default(false);
            $table->text('community_impact_description')->nullable();

            // Bloc gouvernance
            $table->boolean('has_board')->default(false);
            $table->integer('board_size')->default(0);
            $table->boolean('financial_statements_available')->default(false);
            $table->boolean('audited_financials')->default(false);
            $table->boolean('anti_corruption_policy')->default(false);
            $table->boolean('esg_policy')->default(false);
            $table->boolean('legal_compliance')->default(true);
            $table->boolean('tax_compliance')->default(true);
            $table->boolean('digital_accounting')->default(false);
            $table->boolean('erp_system')->default(false);

            // Bloc conformité et financement
            $table->boolean('kyc_completed')->default(false);
            $table->boolean('aml_check')->default(false);
            $table->boolean('sanction_screening')->default(false);
            $table->boolean('pep_check')->default(false);
            $table->boolean('seeking_investment')->default(false);
            $table->decimal('investment_needed', 15, 2)->default(0);
            $table->string('investment_stage')->nullable();
            $table->boolean('business_plan_available')->default(false);
            $table->boolean('previous_grants')->default(false);
            $table->decimal('grant_amount_received', 15, 2)->default(0);
            $table->decimal('outstanding_loans', 15, 2)->default(0);
            $table->boolean('export_potential')->default(false);
            $table->boolean('local_value_chain')->default(false);
            $table->boolean('agri_value_chain')->default(false);
            $table->boolean('digital_economy')->default(false);
            $table->boolean('green_economy')->default(false);
            $table->json('sdg_alignment')->nullable();

            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entreprise_evaluation_profiles');
    }
};
