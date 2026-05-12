<?php

namespace Modules\Simulator\Http\Requests;

class PersistScenarioRequest extends SimulateRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'name' => ['nullable', 'string', 'max:255'],
            'dossier_id' => ['nullable', 'integer'],
            'dossier_instruction_programme_id' => ['nullable', 'integer'],
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $dossierId = $this->input('dossier_id');
            $dipId = $this->input('dossier_instruction_programme_id');
            if ($dossierId !== null && $dipId !== null) {
                $v->errors()->add(
                    'dossier_id',
                    'Choisir un seul rattachement : dossier global ou ligne dossier-programme.'
                );
            }
        });
    }
}
