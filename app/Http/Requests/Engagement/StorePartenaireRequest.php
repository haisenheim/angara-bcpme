<?php

namespace App\Http\Requests\Engagement;

use App\Models\Banque;
use Illuminate\Foundation\Http\FormRequest;

class StorePartenaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'kind' => [
                'required', 'string',
                'in:'.implode(',', array_keys(Banque::KIND_LABELS)),
            ],
            'siege' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'name' => $this->input('name'),
            'kind' => $this->input('kind'),
            'microfinance' => $this->input('kind') === Banque::KIND_EMF ? 1 : 0,
            'siege' => $this->input('siege'),
            'address' => $this->input('address'),
            'actif' => true,
        ];
    }
}
