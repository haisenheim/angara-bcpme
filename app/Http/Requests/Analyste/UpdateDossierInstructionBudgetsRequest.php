<?php

namespace App\Http\Requests\Analyste;

use App\Models\Dossier;
use App\Models\DossierInstructionProgramme;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDossierInstructionBudgetsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Dossier $dossier */
        $dossier = $this->route('dossier');
        $n = $dossier instanceof Dossier ? $dossier->instructionProgrammes()->count() : 0;

        $rules = [
            'engagements_sollicites_total' => ['required', 'numeric', 'min:0'],
            'engagements_en_cours_total' => ['required', 'numeric', 'min:0'],
            'programme_budgets' => [
                $n > 0 ? 'required' : 'sometimes',
                'array',
                'size:'.$n,
            ],
        ];

        if ($n > 0) {
            $rules['programme_budgets.*.id'] = [
                'required',
                'integer',
                'distinct',
                Rule::exists(DossierInstructionProgramme::class, 'id')->where('dossier_id', $dossier->id),
            ];
            $rules['programme_budgets.*.budget_appui_financier'] = ['nullable', 'numeric', 'min:0'];
            $rules['programme_budgets.*.budget_appui_non_financier'] = ['nullable', 'numeric', 'min:0'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'engagements_sollicites_total' => 'total des engagements sollicités',
            'engagements_en_cours_total' => 'total des engagements en cours',
            'programme_budgets' => 'budgets par programme',
        ];
    }
}
