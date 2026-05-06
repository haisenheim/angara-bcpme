<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Regional/dashboard');
    }

    public function getStats()
    {
        $rid = (int) auth()->user()->representation_id;

        return response()->json([
            'entreprises' => Entreprise::query()->where('representation_id', $rid)->count(),
            'dossiers' => Dossier::query()->where('representation_id', $rid)->count(),
            'prospects' => Entreprise::query()
                ->where('representation_id', $rid)
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
        ]);
    }
}
