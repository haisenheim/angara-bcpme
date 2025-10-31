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

        $stats = [
            'total_dossiers' => Dossier::where('analyste_id', $analysteId)->count(),
            'pending_analysis' => Dossier::where('analyste_id', $analysteId)->where('statut', 'en_attente')->count(),
            'completed_analysis' => Dossier::where('analyste_id', $analysteId)->where('statut', 'termine')->count(),
            'total_entreprises' => Entreprise::where('user_id', $analysteId)->count(),
            'in_progress' => Dossier::where('analyste_id', $analysteId)->where('statut', 'en_cours')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get dossiers distribution data for chart
     */
    public function getDossiersDistribution()
    {
        $analysteId = auth()->user()->id;

        $distribution = [
            'en_cours' => Dossier::where('analyste_id', $analysteId)->where('statut', 'en_cours')->count(),
            'en_attente' => Dossier::where('analyste_id', $analysteId)->where('statut', 'en_attente')->count(),
            'termine' => Dossier::where('analyste_id', $analysteId)->where('statut', 'termine')->count(),
            'rejete' => Dossier::where('analyste_id', $analysteId)->where('statut', 'rejete')->count(),
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

            $data[] = Dossier::where('analyste_id', $analysteId)
                ->where('statut', 'termine')
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
                return [
                    'id' => $dossier->id,
                    'token' => $dossier->token,
                    'entreprise_name' => $dossier->entreprise->name ?? 'N/A',
                    'programme_name' => $dossier->programme->name ?? 'N/A',
                    'statut' => $dossier->statut ?? 'en_attente',
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
        $completedDossiers = Dossier::where('analyste_id', $analysteId)->where('statut', 'termine')->count();
        $completionRate = $totalDossiers > 0 ? round(($completedDossiers / $totalDossiers) * 100) : 0;

        $metrics = [
            'completion_rate' => $completionRate,
            'this_month_completed' => Dossier::where('analyste_id', $analysteId)
                ->where('statut', 'termine')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'total_analyzed' => $completedDossiers,
            'pending_count' => Dossier::where('analyste_id', $analysteId)->where('statut', 'en_attente')->count(),
        ];

        return response()->json($metrics);
    }

    /**
     * Get alerts
     */
    public function getAlerts()
    {
        $analysteId = auth()->user()->id;

        $urgentDossiers = Dossier::where('analyste_id', $analysteId)
            ->where('statut', 'en_attente')
            ->where('created_at', '<=', now()->subDays(3))
            ->count();

        $inProgressDossiers = Dossier::where('analyste_id', $analysteId)
            ->where('statut', 'en_cours')
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
