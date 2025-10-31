<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Structuration\Cooperative;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Ca/dashboard');
	}

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $agenceId = auth()->user()->agence_id;

        $stats = [
            'total_dossiers' => Dossier::where('agence_id', $agenceId)->count(),
            'dossiers_en_cours' => Dossier::where('agence_id', $agenceId)->where('statut', 'en_cours')->count(),
            'total_entreprises' => Entreprise::where('agence_id', $agenceId)->count(),
            'total_cooperatives' => Cooperative::where('agence_id', $agenceId)->count(),
            'total_users' => User::where('agence_id', $agenceId)->where('active', 1)->count(),
            'total_prospects' => Entreprise::where('agence_id', $agenceId)->where('statut', 'prospect')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get performance chart data
     */
    public function getPerformanceData()
    {
        $agenceId = auth()->user()->agence_id;
        $months = [];
        $dossiersData = [];
        $entreprisesData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $dossiersData[] = Dossier::where('agence_id', $agenceId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $entreprisesData[] = Entreprise::where('agence_id', $agenceId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return response()->json([
            'labels' => $months,
            'dossiers' => $dossiersData,
            'entreprises' => $entreprisesData,
        ]);
    }

    /**
     * Get team performance data
     */
    public function getTeamPerformance()
    {
        $agenceId = auth()->user()->agence_id;

        $teamMembers = User::where('agence_id', $agenceId)
            ->where('active', 1)
            ->with('role')
            ->limit(10)
            ->get()
            ->map(function($member) {
                $dossierCount = 0;
                if (in_array($member->role_id, [3, 4])) { // Gestionnaire or Analyste
                    $column = $member->role_id == 3 ? 'gestionnaire_id' : 'analyste_id';
                    $dossierCount = Dossier::where($column, $member->id)->count();
                }

                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'role' => $member->role->name ?? 'N/A',
                    'dossiers_count' => $dossierCount,
                    'active' => $member->active,
                ];
            });

        return response()->json($teamMembers);
    }

    /**
     * Get recent dossiers
     */
    public function getRecentDossiers()
    {
        $agenceId = auth()->user()->agence_id;

        $dossiers = Dossier::where('agence_id', $agenceId)
            ->with(['entreprise', 'programme', 'gestionnaire'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($dossier) {
                return [
                    'id' => $dossier->id,
                    'token' => $dossier->token,
                    'entreprise_name' => $dossier->entreprise->name ?? 'N/A',
                    'programme_name' => $dossier->programme->name ?? 'N/A',
                    'gestionnaire_name' => $dossier->gestionnaire->name ?? 'Non assigné',
                    'statut' => $dossier->statut ?? 'en_attente',
                    'updated_at' => $dossier->updated_at->diffForHumans(),
                ];
            });

        return response()->json($dossiers);
    }

    /**
     * Get monthly statistics
     */
    public function getMonthlyStats()
    {
        $agenceId = auth()->user()->agence_id;

        $stats = [
            'new_dossiers' => Dossier::where('agence_id', $agenceId)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'new_entreprises' => Entreprise::where('agence_id', $agenceId)
                ->whereMonth('created_at', now()->month)
                ->count(),
            'completed_dossiers' => Dossier::where('agence_id', $agenceId)
                ->where('statut', 'termine')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'active_prospects' => Entreprise::where('agence_id', $agenceId)
                ->where('statut', 'prospect')
                ->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get alerts and notifications
     */
    public function getAlerts()
    {
        $agenceId = auth()->user()->agence_id;

        $alerts = [
            'pending_dossiers' => Dossier::where('agence_id', $agenceId)
                ->where('statut', 'en_attente')
                ->count(),
            'old_pending' => Dossier::where('agence_id', $agenceId)
                ->where('statut', 'en_attente')
                ->where('created_at', '<=', now()->subDays(7))
                ->count(),
            'inactive_users' => User::where('agence_id', $agenceId)
                ->where('active', 0)
                ->count(),
        ];

        return response()->json($alerts);
    }

}
