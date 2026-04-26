<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userToken = $this->route('user');
        $userId = \App\Models\User::where('token', $userToken)->value('id');

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'role_id' => ['required', 'integer', 'exists:profils,id'],
            'organisation_type' => ['required', 'in:agence,entite'],
            'agence_id' => ['nullable', 'integer', 'min:0'],
            'organisation_entite_id' => ['nullable', 'integer', 'min:1', 'exists:organisation_entites,id'],
            'active' => ['nullable', 'boolean'],
        ];

        return $rules;
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $type = (string) ($this->input('organisation_type') ?? '');
            $agenceId = (int) ($this->input('agence_id') ?? 0);
            $entiteId = (int) ($this->input('organisation_entite_id') ?? 0);
            if ($type === 'agence' && $agenceId < 1) {
                $v->errors()->add('agence_id', 'Selectionnez une agence.');
            }
            if ($type === 'entite' && $entiteId < 1) {
                $v->errors()->add('organisation_entite_id', 'Selectionnez une entite.');
            }
        });
    }
}
