<?php

namespace App\Http\Controllers\AnalysteJuridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Mon espace', [
            ['key' => 'dossiers_assignes', 'label' => 'Dossiers affectés', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('analyste-juridique.dossiers.index')],
            ['key' => 'a_traiter', 'label' => 'À traiter', 'icon' => 'edit_note', 'tone' => 'warning'],
            ['key' => 'soumis_reju', 'label' => 'Soumis au resp. juridique', 'icon' => 'task_alt', 'tone' => 'success'],
            ['key' => 'en_retard', 'label' => 'En retard', 'icon' => 'schedule', 'tone' => 'warning'],
        ], fn () => $this->getStats()->getData(true), fn () => collect($this->getTodos()->getData(true)['todos'] ?? [])
            ->map(fn ($t) => $t + [
                'when_human' => $t['assigned_human'] ?? null,
                'kind' => ['label' => 'Affecté', 'variant' => 'info'],
                'href' => $this->routeIfExists('analyste-juridique.dossiers.show', $t['token']),
            ])->values()->all());
    }

    public function getStats()
    {
        $me = auth()->id();
        $base = Dossier::query()
            ->where('juridique_analyste_user_id', $me)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->whereNull('juridique_submitted_to_engagements_at');

        return response()->json([
            'dossiers_assignes' => (clone $base)->count(),
            'a_traiter' => (clone $base)
                ->whereNull('juridique_analyste_submitted_to_reju_at')
                ->count(),
            'soumis_reju' => (clone $base)
                ->whereNotNull('juridique_analyste_submitted_to_reju_at')
                ->count(),
            'en_retard' => (clone $base)
                ->whereNull('juridique_analyste_submitted_to_reju_at')
                ->whereNotNull('juridique_analyste_assigned_at')
                ->where('juridique_analyste_assigned_at', '<=', now()->subDays(3))
                ->count(),
            // Info contexte: prospects soumis (banque) — utile mais secondaire.
            'prospects_soumis' => Entreprise::query()
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
        ]);
    }

    public function getTodos()
    {
        $me = auth()->id();

        $rows = Dossier::query()
            ->where('juridique_analyste_user_id', $me)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->whereNull('juridique_submitted_to_engagements_at')
            ->whereNull('juridique_analyste_submitted_to_reju_at')
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy('juridique_analyste_assigned_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) {
                $assignedAt = $d->juridique_analyste_assigned_at instanceof Carbon ? $d->juridique_analyste_assigned_at : null;

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
