<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        $agenceId = auth()->user()->agence_id;

        $pendingQualif = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($e) => $e->whereNull('programmes_submitted_at'));
            })
            ->count();

        $clientsCount = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->count();

        $instructionPending = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
            ->count();

        $instructionEnCours = Dossier::query()
            ->where('agence_id', $agenceId)
            ->count();

        return view('ChefFiliere.dashboard', compact(
            'pendingQualif',
            'clientsCount',
            'instructionPending',
            'instructionEnCours'
        ));
    }
}
