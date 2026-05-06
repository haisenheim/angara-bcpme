<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Programme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /** Même périmètre que `Analyste\DossierController::baseQuery()` (banque entière si AFE sans agence). */
    private function dossiersQueryForCurrentAnalyste()
    {
        $user = auth()->user();
        if ($user instanceof User && $user->isAnalysteFinancierNational()) {
            return Dossier::query();
        }

        return Dossier::where('analyste_id', $user->id);
    }

    public function index()
	{
		return view('Analyste/dashboard');
	}

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $analysteId = auth()->user()->id;
        $base = $this->dossiersQueryForCurrentAnalyste();

        // Dossiers with indicateurs are "en cours" or completed
        $enCours = (clone $base)->whereHas('indicateurs')->count();
        // Dossiers without indicateurs are "en attente"
        $enAttente = (clone $base)->whereDoesntHave('indicateurs')->count();

        $user = auth()->user();
        $totalEntreprises = ($user instanceof User && $user->isAnalysteFinancierNational())
            ? Entreprise::where('prospect', 0)->whereHas('dossiers')->count()
            : Entreprise::where('user_id', $analysteId)->count();

        $stats = [
            'total_dossiers' => (clone $base)->count(),
            'pending_analysis' => $enAttente,
            'completed_analysis' => $enCours,
            'total_entreprises' => $totalEntreprises,
            'in_progress' => $enCours,
            // Workflow instruction côté analyste financier (timestamps existants).
            'pending_submission' => (clone $base)
                ->whereNotNull('analyste_id')
                ->whereNull('exploitation_analyste_transmitted_to_exploitation_at')
                ->count(),
            'submitted_to_exploitation' => (clone $base)
                ->whereNotNull('exploitation_analyste_transmitted_to_exploitation_at')
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get dossiers distribution data for chart
     */
    public function getDossiersDistribution()
    {
        // Get all dossiers and compute status
        $dossiers = $this->dossiersQueryForCurrentAnalyste()->with('indicateurs')->get();

        $distribution = [
            'en_cours' => $dossiers->filter(function($d) {
                return $d->indicateurs->count() > 0;
            })->count(),
            'en_attente' => $dossiers->filter(function($d) {
                return $d->indicateurs->count() == 0;
            })->count(),
            'termine' => 0, // Will need additional logic to determine completed
            'rejete' => 0,  // Will need additional logic to determine rejected
        ];

        return response()->json($distribution);
    }

    /**
     * Get monthly analysis trend data
     */
    public function getMonthlyAnalysis()
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Count dossiers with indicateurs created in this month
            $data[] = (clone $this->dossiersQueryForCurrentAnalyste())
                ->whereHas('indicateurs')
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->count();
        }

        return response()->json([
            'labels' => $months,
            'data' => $data,
        ]);
    }

    /**
     * Get recent dossiers
     */
    public function getRecentDossiers()
    {
        $dossiers = $this->dossiersQueryForCurrentAnalyste()
            ->with(['entreprise', 'programme'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($dossier) {
                $status = $dossier->status;
                return [
                    'id' => $dossier->id,
                    'token' => $dossier->token,
                    'entreprise_name' => $dossier->entreprise->name ?? 'N/A',
                    'programme_name' => $dossier->programme->name ?? 'N/A',
                    'statut' => $status['code'],
                    'statut_name' => $status['name'],
                    'updated_at' => $dossier->updated_at->diffForHumans(),
                ];
            });

        return response()->json($dossiers);
    }

    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics()
    {
        $base = $this->dossiersQueryForCurrentAnalyste();
        $totalDossiers = (clone $base)->count();
        $completedDossiers = (clone $base)->whereHas('indicateurs')->count();
        $completionRate = $totalDossiers > 0 ? round(($completedDossiers / $totalDossiers) * 100) : 0;

        $metrics = [
            'completion_rate' => $completionRate,
            'this_month_completed' => (clone $base)
                ->whereHas('indicateurs')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'total_analyzed' => $completedDossiers,
            'pending_count' => (clone $base)->whereDoesntHave('indicateurs')->count(),
        ];

        return response()->json($metrics);
    }

    /**
     * Get alerts
     */
    public function getAlerts()
    {
        $base = $this->dossiersQueryForCurrentAnalyste();

        // Urgent = pending dossiers older than 3 days
        $urgentDossiers = (clone $base)
            ->whereDoesntHave('indicateurs')
            ->where('created_at', '<=', now()->subDays(3))
            ->count();

        // In progress = dossiers with indicateurs
        $inProgressDossiers = (clone $base)
            ->whereHas('indicateurs')
            ->count();

        return response()->json([
            'urgent_dossiers' => $urgentDossiers,
            'in_progress_dossiers' => $inProgressDossiers,
        ]);
    }

    /**
     * Get programmes overview
     */
    public function getProgrammes()
    {
        $programmes = Programme::limit(5)->get()->map(function($programme) {
            return [
                'id' => $programme->id,
                'name' => $programme->name,
                'dossiers_count' => $programme->dossiers->count(),
            ];
        });

        return response()->json($programmes);
    }

    public function getTodos()
    {
        $base = $this->dossiersQueryForCurrentAnalyste();

        $rows = (clone $base)
            ->whereNotNull('analyste_id')
            ->whereNull('exploitation_analyste_transmitted_to_exploitation_at')
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy('exploitation_analyste_assigned_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) {
                $assignedAt = $d->exploitation_analyste_assigned_at instanceof Carbon ? $d->exploitation_analyste_assigned_at : null;

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
