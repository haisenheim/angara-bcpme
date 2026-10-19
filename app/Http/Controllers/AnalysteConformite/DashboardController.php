<?php

namespace App\Http\Controllers\AnalysteConformite;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Mon espace', [
            ['key' => 'prospects_soumis', 'label' => 'Prospects soumis', 'icon' => 'person_search'],
        ], fn () => $this->getStats()->getData(true));
    }

    public function getStats()
    {
        return response()->json([
            'prospects_soumis' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
        ]);
    }
}
