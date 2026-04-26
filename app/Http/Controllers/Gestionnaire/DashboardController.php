<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Gestionnaire/dashboard');
	}

    /**
     * Get dashboard statistics
     */
    public function getStats()
    {
        $userId = auth()->user()->id;
        $agenceId = auth()->user()->agence_id;

        $stats = [
            'total_entreprises' => Entreprise::where('user_id', $userId)->count(),
            'total_dossiers' => Dossier::where('agence_id', $agenceId)->count(),
            'total_prospects' => Entreprise::where('user_id', $userId)->where('prospect', 1)->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get recent dossiers
     */
    public function getRecentDossiers()
    {
        $agenceId = auth()->user()->agence_id;

        $dossiers = Dossier::where('agence_id', $agenceId)
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
     * Get dossiers distribution for chart
     */
    public function getDossiersDistribution()
    {
        $agenceId = auth()->user()->agence_id;

        $dossiers = Dossier::where('agence_id', $agenceId)->with('indicateurs')->get();

        $distribution = [
            'en_cours' => $dossiers->filter(function($d) {
                return $d->status['code'] == 1;
            })->count(),
            'en_attente' => $dossiers->filter(function($d) {
                return $d->status['code'] == 0;
            })->count(),
            'termine' => $dossiers->filter(function($d) {
                return $d->status['code'] == 2;
            })->count(),
            'rejete' => $dossiers->filter(function($d) {
                return $d->status['code'] == 3;
            })->count(),
        ];

        return response()->json($distribution);
    }

    /**
     * Get entreprises chart data
     */
    public function getEntreprisesData()
    {
        $userId = auth()->user()->id;
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $data[] = Entreprise::where('user_id', $userId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return response()->json([
            'labels' => $months,
            'data' => $data,
        ]);
    }

}
