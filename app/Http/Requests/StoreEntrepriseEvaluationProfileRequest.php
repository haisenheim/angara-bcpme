<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEntrepriseEvaluationProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_framework' => ['nullable', 'string', 'max:100'],
            'business_sector' => ['nullable', 'string', 'max:255'],
            'business_subsector' => ['nullable', 'string', 'max:255'],
            'annual_turnover' => ['nullable', 'numeric', 'min:0'],
            'annual_turnover_year' => ['nullable', 'integer', 'min:1900', 'max:2100'],
            'net_income' => ['nullable', 'numeric'],
            'balance_sheet_total' => ['nullable', 'numeric', 'min:0'],
            'employees_total' => ['nullable', 'integer', 'min:0'],
            'employees_permanent' => ['nullable', 'integer', 'min:0'],
            'employees_temporary' => ['nullable', 'integer', 'min:0'],
            'env_policy' => ['nullable', 'boolean'],
            'env_certified' => ['nullable', 'boolean'],
            'env_certification_type' => ['nullable', 'string', 'max:100'],
            'uses_renewable_energy' => ['nullable', 'boolean'],
            'renewable_energy_ratio' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'waste_management_system' => ['nullable', 'boolean'],
            'water_management_system' => ['nullable', 'boolean'],
            'carbon_measurement' => ['nullable', 'boolean'],
            'estimated_co2_emission' => ['nullable', 'numeric', 'min:0'],
            'climate_risk_exposure' => ['nullable', 'string', 'max:100'],
            'climate_adaptation_strategy' => ['nullable', 'boolean'],
            'women_led' => ['nullable', 'boolean'],
            'youth_led' => ['nullable', 'boolean'],
            'nb_women' => ['nullable', 'integer', 'min:0'],
            'nb_youth' => ['nullable', 'integer', 'min:0'],
            'nb_disabled' => ['nullable', 'integer', 'min:0'],
            'inclusive_business' => ['nullable', 'boolean'],
            'social_protection' => ['nullable', 'boolean'],
            'training_program' => ['nullable', 'boolean'],
            'community_impact' => ['nullable', 'boolean'],
            'community_impact_description' => ['nullable', 'string'],
            'has_board' => ['nullable', 'boolean'],
            'board_size' => ['nullable', 'integer', 'min:0'],
            'financial_statements_available' => ['nullable', 'boolean'],
            'audited_financials' => ['nullable', 'boolean'],
            'anti_corruption_policy' => ['nullable', 'boolean'],
            'esg_policy' => ['nullable', 'boolean'],
            'legal_compliance' => ['nullable', 'boolean'],
            'tax_compliance' => ['nullable', 'boolean'],
            'digital_accounting' => ['nullable', 'boolean'],
            'erp_system' => ['nullable', 'boolean'],
            'kyc_completed' => ['nullable', 'boolean'],
            'aml_check' => ['nullable', 'boolean'],
            'sanction_screening' => ['nullable', 'boolean'],
            'pep_check' => ['nullable', 'boolean'],
            'seeking_investment' => ['nullable', 'boolean'],
            'investment_needed' => ['nullable', 'numeric', 'min:0'],
            'investment_stage' => ['nullable', 'string', 'max:100'],
            'business_plan_available' => ['nullable', 'boolean'],
            'previous_grants' => ['nullable', 'boolean'],
            'grant_amount_received' => ['nullable', 'numeric', 'min:0'],
            'outstanding_loans' => ['nullable', 'numeric', 'min:0'],
            'export_potential' => ['nullable', 'boolean'],
            'local_value_chain' => ['nullable', 'boolean'],
            'agri_value_chain' => ['nullable', 'boolean'],
            'digital_economy' => ['nullable', 'boolean'],
            'green_economy' => ['nullable', 'boolean'],
            'sdg_alignment' => ['nullable', 'array'],
        ];
    }
}
