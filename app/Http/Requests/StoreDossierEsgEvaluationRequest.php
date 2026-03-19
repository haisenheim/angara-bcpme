<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDossierEsgEvaluationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference_framework' => ['nullable', 'string', 'max:100'],
            'evaluation_type' => ['nullable', 'string', 'max:50'],
            'evaluation_date' => ['nullable', 'date'],
            'score_environmental' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_social' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_governance' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_financial' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_compliance' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'score_global' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'risk_level' => ['nullable', 'string', 'max:50'],
            'bankability_level' => ['nullable', 'string', 'max:50'],
            'eligibility_blending' => ['nullable', 'boolean'],
            'eligibility_guarantee' => ['nullable', 'boolean'],
            'eligibility_global_gateway' => ['nullable', 'boolean'],
            'exclusion_flag' => ['nullable', 'boolean'],
            'minimum_compliance_passed' => ['nullable', 'boolean'],
            'sdg_alignment' => ['nullable', 'array'],
            'strengths' => ['nullable', 'string'],
            'weaknesses' => ['nullable', 'string'],
            'recommendations' => ['nullable', 'string'],
            'due_diligence_notes' => ['nullable', 'string'],
            'analyst_conclusion' => ['nullable', 'string'],
        ];
    }
}
