<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEvaluationFrameworkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', Rule::unique('evaluation_frameworks', 'code')->ignore($this->route('evaluation_framework'))],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'applies_to' => ['nullable', 'string', 'max:100'],
        ];
    }
}
