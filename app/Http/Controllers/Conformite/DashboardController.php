<?php

namespace App\Http\Controllers\Conformite;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Espace Responsable Conformité', [
            ['key' => 'pending_prospects', 'label' => 'Avis prospects à rendre', 'icon' => 'rate_review', 'tone' => 'warning', 'href' => $this->routeIfExists('conformite.prospects.index')],
            ['key' => 'bloques_juridique', 'label' => 'En attente de l\'avis juridique', 'icon' => 'hourglass_top'],
        ], fn () => $this->getStats()->getData(true), fn () => collect($this->getTodos()->getData(true)['prospects'] ?? [])
            ->map(fn ($p) => [
                'token' => $p['token'], 'entreprise' => $p['name'], 'programmes' => 'Prospect — avis conformité attendu',
                'when_human' => $p['when_human'], 'kind' => ['label' => 'Avis prospect', 'variant' => 'warning'],
                'href' => $this->routeIfExists('conformite.prospects.show', $p['token']),
            ])->values()->all());
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
