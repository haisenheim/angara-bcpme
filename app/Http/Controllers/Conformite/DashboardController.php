<?php

namespace App\Http\Controllers\Conformite;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Conformite.dashboard');
    }

    public function getStats()
    {
        $base = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNull('promu_client_at')
            ->whereNull('prospect_rejected_at');

        return response()->json([
            'pending_prospects' => (clone $base)
                ->whereNotNull('juridique_avis_at')
                ->whereNull('conformite_avis_at')
                ->count(),
            'bloques_juridique' => (clone $base)
                ->whereNull('juridique_avis_at')
                ->count(),
        ]);
    }

    public function getTodos()
    {
        $pending = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->whereNotNull('juridique_avis_at')
            ->whereNull('conformite_avis_at')
            ->whereNull('promu_client_at')
            ->whereNull('prospect_rejected_at')
            ->orderBy('juridique_avis_at', 'asc')
            ->limit(10)
            ->get(['token', 'name', 'juridique_avis_at'])
            ->map(function (Entreprise $e) {
                $dt = $e->juridique_avis_at instanceof Carbon ? $e->juridique_avis_at : null;
                return [
                    'token' => $e->token,
                    'name' => $e->name ?? '—',
                    'when_human' => $dt?->diffForHumans(),
                ];
            })
            ->values();

        return response()->json([
            'prospects' => $pending,
        ]);
    }
}
