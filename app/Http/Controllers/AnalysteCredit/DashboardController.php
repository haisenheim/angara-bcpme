<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('AnalysteCredit.dashboard');
    }

    public function getStats()
    {
        $me = auth()->id();
        $base = Dossier::query()
            ->where('reng_analyste_credit_user_id', $me)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->whereNull('reng_submitted_to_risques_at');

        return response()->json([
            'dossiers_count' => (clone $base)->count(),
            'a_traiter' => (clone $base)
                ->whereNull('reng_analyste_credit_submitted_at')
                ->count(),
            'soumis_reng' => (clone $base)
                ->whereNotNull('reng_analyste_credit_submitted_at')
                ->count(),
            'en_retard' => (clone $base)
                ->whereNull('reng_analyste_credit_submitted_at')
                ->whereNotNull('reng_analyste_credit_assigned_at')
                ->where('reng_analyste_credit_assigned_at', '<=', now()->subDays(3))
                ->count(),
        ]);
    }

    public function getTodos()
    {
        $me = auth()->id();

        $rows = Dossier::query()
            ->where('reng_analyste_credit_user_id', $me)
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->whereNull('reng_submitted_to_risques_at')
            ->whereNull('reng_analyste_credit_submitted_at')
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy('reng_analyste_credit_assigned_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) {
                $assignedAt = $d->reng_analyste_credit_assigned_at instanceof Carbon ? $d->reng_analyste_credit_assigned_at : null;

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
