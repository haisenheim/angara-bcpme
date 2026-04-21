<?php

namespace App\Http\Controllers\AnalysteRisques;

use App\Http\Controllers\Controller;
use App\Models\Dossier;

class DashboardController extends Controller
{
    public function index()
    {
        $dossiersCount = Dossier::query()
            ->where('rerx_analyste_risques_user_id', auth()->id())
            ->whereNotNull('reng_submitted_to_risques_at')
            ->count();

        return view('AnalysteRisques.dashboard', [
            'dossiersCount' => $dossiersCount,
        ]);
    }
}
