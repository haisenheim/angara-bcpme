<?php

namespace App\Http\Controllers\ChefAgence;

use App\Http\Controllers\Controller;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ChefAgence.dashboard', [
            'pendingCount' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->where('agence_id', auth()->user()->agence_id)
                ->count(),
            'instructionCount' => DossierEntreeRelation::query()
                ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
                ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
                ->count(),
        ]);
    }
}
