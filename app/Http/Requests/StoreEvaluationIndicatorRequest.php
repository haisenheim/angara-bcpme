<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEvaluationIndicatorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'framework_id' => ['nullable', 'exists:evaluation_frameworks,id'],
            'category_id' => ['nullable', 'exists:evaluation_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:evaluation_indicators,code'],
            'description' => ['nullable', 'string'],
            'input_type' => ['nullable', 'string', 'max:50'],
            'score_type' => ['nullable', 'string', 'max:50'],
            'default_weight' => ['nullable', 'numeric', 'min:0'],
            'min_score' => ['nullable', 'numeric'],
            'max_score' => ['nullable', 'numeric'],
            'required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
