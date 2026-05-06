<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        // Compatibilité: certaines données historiques utilisent `user_id` (créateur),
        // le workflow prospect utilise `gestionnaire_id`.
        $myEntreprises = Entreprise::query()
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->orWhere('gestionnaire_id', $userId);
            });

        $myProspects = (clone $myEntreprises)->where('prospect', true);

        $stats = [
            'total_entreprises' => (clone $myEntreprises)->count(),
            'total_dossiers' => Dossier::where('agence_id', $agenceId)->count(),
            'my_dossiers' => Dossier::query()->where('gestionnaire_id', $userId)->count(),
            'total_prospects' => (clone $myProspects)->count(),

            // Workflow prospect (gestionnaire): brouillon / soumis / bloqués / prêts décision.
            'prospects_brouillon' => (clone $myProspects)
                ->whereNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->count(),
            'prospects_soumis' => (clone $myProspects)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->count(),
            'prospects_bloques_avis' => (clone $myProspects)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->where(function ($q) {
                    $q->whereNull('juridique_avis_at')
                        ->orWhereNull('conformite_avis_at');
                })
                ->count(),
            'prospects_prets_arbitrage' => (clone $myProspects)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->whereNotNull('juridique_avis_at')
                ->whereNotNull('conformite_avis_at')
                ->count(),
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

    public function getTodos()
    {
        $userId = auth()->id();

        $myProspects = Entreprise::query()
            ->where('prospect', true)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                    ->orWhere('gestionnaire_id', $userId);
            })
            ->whereNull('promu_client_at')
            ->whereNull('prospect_rejected_at');

        $drafts = (clone $myProspects)
            ->whereNull('prospect_submitted_at')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get(['token', 'name', 'updated_at'])
            ->map(function (Entreprise $e) {
                $at = $e->updated_at instanceof Carbon ? $e->updated_at : null;

                return [
                    'token' => $e->token,
                    'name' => $e->name ?? '—',
                    'when_human' => $at?->diffForHumans(),
                ];
            })
            ->values();

        $blocked = (clone $myProspects)
            ->whereNotNull('prospect_submitted_at')
            ->where(function ($q) {
                $q->whereNull('juridique_avis_at')
                    ->orWhereNull('conformite_avis_at');
            })
            ->orderBy('prospect_submitted_at', 'asc')
            ->limit(10)
            ->get(['token', 'name', 'prospect_submitted_at', 'juridique_avis_at', 'conformite_avis_at'])
            ->map(function (Entreprise $e) {
                $submitted = $e->prospect_submitted_at instanceof Carbon ? $e->prospect_submitted_at : null;
                $missing = [];
                if (! $e->juridique_avis_at) {
                    $missing[] = 'avis juridique';
                }
                if (! $e->conformite_avis_at) {
                    $missing[] = 'avis conformité';
                }

                return [
                    'token' => $e->token,
                    'name' => $e->name ?? '—',
                    'when_human' => $submitted?->diffForHumans(),
                    'missing' => implode(' · ', $missing),
                ];
            })
            ->values();

        return response()->json([
            'drafts' => $drafts,
            'blocked' => $blocked,
        ]);
    }

}
