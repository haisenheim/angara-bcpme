<?php

namespace App\Http\Requests\Engagement;

use App\Models\Engagement\EngagementLigne;
use Illuminate\Foundation\Http\FormRequest;

class UpdateEngagementLigneRequest extends FormRequest
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
            'partenaire_id' => ['nullable', 'integer', 'exists:central_app_mysql.banques,id'],

            'encours_initial' => ['nullable', 'numeric', 'min:0'],
            'encours_actuel' => ['nullable', 'numeric', 'min:0'],
            'encours_remboursement_n1' => ['nullable', 'numeric', 'min:0'],
            'encours_retards' => ['nullable', 'numeric', 'min:0'],
            'encours_impayes' => ['nullable', 'numeric', 'min:0'],
            'encours_statut' => ['nullable', 'string', 'max:64', 'in:'.implode(',', array_keys(EngagementLigne::STATUTS))],
            'encours_date_validite' => ['nullable', 'date'],

            'sollicite_montant' => ['nullable', 'numeric', 'min:0'],
            'sollicite_date_validite' => ['nullable', 'date'],

            'commentaire' => ['nullable', 'string', 'max:5000'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'partenaire_id' => $this->filled('partenaire_id') ? (int) $this->input('partenaire_id') : null,
            'encours_initial' => (float) $this->input('encours_initial', 0),
            'encours_actuel' => (float) $this->input('encours_actuel', 0),
            'encours_remboursement_n1' => (float) $this->input('encours_remboursement_n1', 0),
            'encours_retards' => (float) $this->input('encours_retards', 0),
            'encours_impayes' => (float) $this->input('encours_impayes', 0),
            'encours_statut' => $this->input('encours_statut'),
            'encours_date_validite' => $this->input('encours_date_validite'),
            'sollicite_montant' => (float) $this->input('sollicite_montant', 0),
            'sollicite_date_validite' => $this->input('sollicite_date_validite'),
            'commentaire' => $this->input('commentaire'),
        ];
    }
}
