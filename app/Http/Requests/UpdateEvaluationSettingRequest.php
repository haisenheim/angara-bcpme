<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEvaluationSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:100'],
            'value' => ['nullable'],
            'type' => ['nullable', 'string', 'in:string,json,boolean,integer,decimal'],
            'description' => ['nullable', 'string'],
            'group' => ['nullable', 'string', 'max:50'],
        ];
    }
}
