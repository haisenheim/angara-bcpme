<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseEvaluationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'entreprise_id', 'gestionnaire_id', 'agence_id',
        'reference_framework', 'business_sector', 'business_subsector',
        'annual_turnover', 'annual_turnover_year', 'net_income', 'balance_sheet_total',
        'employees_total', 'employees_permanent', 'employees_temporary',
        'env_policy', 'env_certified', 'env_certification_type', 'uses_renewable_energy',
        'renewable_energy_ratio', 'waste_management_system', 'water_management_system',
        'carbon_measurement', 'estimated_co2_emission', 'climate_risk_exposure', 'climate_adaptation_strategy',
        'women_led', 'youth_led', 'nb_women', 'nb_youth', 'nb_disabled',
        'inclusive_business', 'social_protection', 'training_program', 'community_impact', 'community_impact_description',
        'has_board', 'board_size', 'financial_statements_available', 'audited_financials',
        'anti_corruption_policy', 'esg_policy', 'legal_compliance', 'tax_compliance',
        'digital_accounting', 'erp_system',
        'kyc_completed', 'aml_check', 'sanction_screening', 'pep_check',
        'seeking_investment', 'investment_needed', 'investment_stage', 'business_plan_available',
        'previous_grants', 'grant_amount_received', 'outstanding_loans',
        'export_potential', 'local_value_chain', 'agri_value_chain', 'digital_economy', 'green_economy',
        'sdg_alignment', 'last_updated_by',
    ];

    protected $casts = [
        'annual_turnover' => 'decimal:2',
        'net_income' => 'decimal:2',
        'balance_sheet_total' => 'decimal:2',
        'renewable_energy_ratio' => 'decimal:2',
        'estimated_co2_emission' => 'decimal:2',
        'investment_needed' => 'decimal:2',
        'grant_amount_received' => 'decimal:2',
        'outstanding_loans' => 'decimal:2',
        'sdg_alignment' => 'array',
        'env_policy' => 'boolean',
        'env_certified' => 'boolean',
        'uses_renewable_energy' => 'boolean',
        'waste_management_system' => 'boolean',
        'water_management_system' => 'boolean',
        'carbon_measurement' => 'boolean',
        'climate_adaptation_strategy' => 'boolean',
        'women_led' => 'boolean',
        'youth_led' => 'boolean',
        'inclusive_business' => 'boolean',
        'social_protection' => 'boolean',
        'training_program' => 'boolean',
        'community_impact' => 'boolean',
        'has_board' => 'boolean',
        'financial_statements_available' => 'boolean',
        'audited_financials' => 'boolean',
        'anti_corruption_policy' => 'boolean',
        'esg_policy' => 'boolean',
        'legal_compliance' => 'boolean',
        'tax_compliance' => 'boolean',
        'digital_accounting' => 'boolean',
        'erp_system' => 'boolean',
        'kyc_completed' => 'boolean',
        'aml_check' => 'boolean',
        'sanction_screening' => 'boolean',
        'pep_check' => 'boolean',
        'seeking_investment' => 'boolean',
        'business_plan_available' => 'boolean',
        'previous_grants' => 'boolean',
        'export_potential' => 'boolean',
        'local_value_chain' => 'boolean',
        'agri_value_chain' => 'boolean',
        'digital_economy' => 'boolean',
        'green_economy' => 'boolean',
    ];

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function agence()
    {
        return $this->belongsTo(Agence::class, 'agence_id');
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }
}
