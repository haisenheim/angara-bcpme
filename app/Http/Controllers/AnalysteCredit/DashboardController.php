<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Dossier;

class DashboardController extends Controller
{
    public function index()
    {
        $dossiersCount = Dossier::query()
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->count();

        return view('AnalysteCredit.dashboard', [
            'dossiersCount' => $dossiersCount,
        ]);
    }
}
