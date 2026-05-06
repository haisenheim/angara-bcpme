<?php

namespace App\Http\Controllers\AnalysteRisques;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('AnalysteRisques.dashboard');
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
