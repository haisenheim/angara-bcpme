<?php

namespace App\Http\Controllers\AnalysteJuridique;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('AnalysteJuridique.dashboard', [
            'pendingProspects' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
        ]);
    }
}
