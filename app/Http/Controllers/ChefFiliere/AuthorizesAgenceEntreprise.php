<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Models\Entreprise;

trait AuthorizesAgenceEntreprise
{
    protected function entrepriseForAgence(string $token): Entreprise
    {
        return Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->whereNotNull('promu_client_at')
            ->firstOrFail();
    }
}
