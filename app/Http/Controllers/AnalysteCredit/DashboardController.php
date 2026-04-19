<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Dossier;

class DashboardController extends Controller
{
    public function index()
    {
        $agenceId = auth()->user()?->agence_id;

        return view('AnalysteCredit.dashboard', [
            'dossiersCount' => $agenceId ? Dossier::query()->where('agence_id', $agenceId)->count() : 0,
        ]);
    }
}
