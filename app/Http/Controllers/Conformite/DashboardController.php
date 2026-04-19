<?php

namespace App\Http\Controllers\Conformite;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Conformite.dashboard', [
            'pendingCount' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->whereNotNull('juridique_avis_at')
                ->whereNull('conformite_avis_at')
                ->count(),
        ]);
    }
}
