<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Juridique.dashboard', [
            'pendingCount' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('juridique_avis_at')
                ->count(),
            'instructionDossiersCount' => Dossier::query()
                ->whereNotNull('juridique_instruction_submitted_at')
                ->count(),
        ]);
    }
}
