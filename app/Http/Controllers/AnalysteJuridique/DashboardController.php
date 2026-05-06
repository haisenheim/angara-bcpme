<?php

namespace App\Http\Controllers\AnalysteJuridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('AnalysteJuridique.dashboard');
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
