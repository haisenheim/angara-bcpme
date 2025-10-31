<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Programme;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

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

        // Dossiers with indicateurs are "en cours" or completed
        $enCours = Dossier::where('analyste_id', $analysteId)->whereHas('indicateurs')->count();
        // Dossiers without indicateurs are "en attente"
        $enAttente = Dossier::where('analyste_id', $analysteId)->whereDoesntHave('indicateurs')->count();

        $stats = [
            'total_dossiers' => Dossier::where('analyste_id', $analysteId)->count(),
            'pending_analysis' => $enAttente,
            'completed_analysis' => $enCours,
            'total_entreprises' => Entreprise::where('user_id', $analysteId)->count(),
            'in_progress' => $enCours,
        ];

        return response()->json($stats);
    }

    /**
     * Get dossiers distribution data for chart
     */
    public function getDossiersDistribution()
    {
        $analysteId = auth()->user()->id;

        // Get all dossiers and compute status
        $dossiers = Dossier::where('analyste_id', $analysteId)->with('indicateurs')->get();

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
        $analysteId = auth()->user()->id;
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Count dossiers with indicateurs created in this month
            $data[] = Dossier::where('analyste_id', $analysteId)
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
        $analysteId = auth()->user()->id;

        $dossiers = Dossier::where('analyste_id', $analysteId)
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
        $analysteId = auth()->user()->id;

        $totalDossiers = Dossier::where('analyste_id', $analysteId)->count();
        $completedDossiers = Dossier::where('analyste_id', $analysteId)->whereHas('indicateurs')->count();
        $completionRate = $totalDossiers > 0 ? round(($completedDossiers / $totalDossiers) * 100) : 0;

        $metrics = [
            'completion_rate' => $completionRate,
            'this_month_completed' => Dossier::where('analyste_id', $analysteId)
                ->whereHas('indicateurs')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'total_analyzed' => $completedDossiers,
            'pending_count' => Dossier::where('analyste_id', $analysteId)->whereDoesntHave('indicateurs')->count(),
        ];

        return response()->json($metrics);
    }

    /**
     * Get alerts
     */
    public function getAlerts()
    {
        $analysteId = auth()->user()->id;

        // Urgent = pending dossiers older than 3 days
        $urgentDossiers = Dossier::where('analyste_id', $analysteId)
            ->whereDoesntHave('indicateurs')
            ->where('created_at', '<=', now()->subDays(3))
            ->count();

        // In progress = dossiers with indicateurs
        $inProgressDossiers = Dossier::where('analyste_id', $analysteId)
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

}
