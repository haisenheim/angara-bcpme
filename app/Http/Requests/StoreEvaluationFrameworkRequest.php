<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationFrameworkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:evaluation_frameworks,code'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
            'applies_to' => ['nullable', 'string', 'max:100'],
        ];
    }
}
