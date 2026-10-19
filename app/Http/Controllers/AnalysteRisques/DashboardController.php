<?php

namespace App\Http\Controllers\AnalysteRisques;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Mon espace', [
            ['key' => 'dossiers_count', 'label' => 'Dossiers affectés', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('analyste-risques.dossiers.index')],
            ['key' => 'a_traiter', 'label' => 'À traiter', 'icon' => 'edit_note', 'tone' => 'warning'],
            ['key' => 'soumis_rerx', 'label' => 'Soumis au resp. risques', 'icon' => 'task_alt', 'tone' => 'success'],
            ['key' => 'en_retard', 'label' => 'En retard', 'icon' => 'schedule', 'tone' => 'warning'],
        ], fn () => $this->getStats()->getData(true), fn () => collect($this->getTodos()->getData(true)['todos'] ?? [])
            ->map(fn ($t) => $t + [
                'when_human' => $t['assigned_human'] ?? null,
                'kind' => ['label' => 'Affecté', 'variant' => 'info'],
                'href' => $this->routeIfExists('analyste-risques.dossiers.show', $t['token']),
            ])->values()->all());
    }

    public function getStats()
    {
        $me = auth()->id();
        $base = Dossier::query()
            ->where('rerx_analyste_risques_user_id', $me)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->whereNull('rerx_submitted_to_direction_at');

        return response()->json([
            'dossiers_count' => (clone $base)->count(),
            'a_traiter' => (clone $base)
                ->whereNull('rerx_analyste_risques_submitted_at')
                ->count(),
            'soumis_rerx' => (clone $base)
                ->whereNotNull('rerx_analyste_risques_submitted_at')
                ->count(),
            'en_retard' => (clone $base)
                ->whereNull('rerx_analyste_risques_submitted_at')
                ->whereNotNull('rerx_analyste_risques_assigned_at')
                ->where('rerx_analyste_risques_assigned_at', '<=', now()->subDays(3))
                ->count(),
        ]);
    }

    public function getTodos()
    {
        $me = auth()->id();

        $rows = Dossier::query()
            ->where('rerx_analyste_risques_user_id', $me)
            ->whereNotNull('reng_submitted_to_risques_at')
            ->whereNull('rerx_submitted_to_direction_at')
            ->whereNull('rerx_analyste_risques_submitted_at')
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy('rerx_analyste_risques_assigned_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) {
                $assignedAt = $d->rerx_analyste_risques_assigned_at instanceof Carbon ? $d->rerx_analyste_risques_assigned_at : null;

                return [
                    'token' => $d->token,
                    'entreprise' => $d->entreprise?->name ?? '—',
                    'programmes' => method_exists($d, 'programmesLabel') ? $d->programmesLabel() : ($d->programme?->name ?? '—'),
                    'assigned_at' => $assignedAt?->toDateTimeString(),
                    'assigned_human' => $assignedAt?->diffForHumans(),
                ];
            })
            ->values();

        return response()->json([
            'todos' => $rows,
        ]);
    }
}
