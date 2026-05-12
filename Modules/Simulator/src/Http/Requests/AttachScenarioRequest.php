<?php

namespace Modules\Simulator\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttachScenarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dossier_id' => ['nullable', 'integer'],
            'dossier_instruction_programme_id' => ['nullable', 'integer'],
        ];
    }
}
