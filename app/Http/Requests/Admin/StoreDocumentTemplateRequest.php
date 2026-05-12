<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'file' => [
                'required',
                'file',
                'max:15360',
                'mimes:pdf,doc,docx,xls,xlsx,csv,txt,png,jpeg,jpg,odt,ods',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Veuillez sélectionner un fichier.',
            'file.max' => 'Le fichier ne doit pas dépasser 15 Mo.',
            'file.mimes' => 'Formats acceptés : PDF, Word, Excel, CSV, texte, images, OpenDocument.',
        ];
    }
}
