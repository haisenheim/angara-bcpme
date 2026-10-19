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
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

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
        $dossierShow = fn (?string $token) => $token ? $this->routeIfExists('analyste.dossiers.show', $token) : null;

        return $this->renderInertiaDashboard('Tableau de bord analyste financier', [
            ['key' => 'total_dossiers', 'label' => 'Dossiers assignés', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('analyste.dossiers.index')],
            ['key' => 'pending_analysis', 'label' => 'En attente d\'analyse', 'icon' => 'hourglass_top', 'tone' => 'warning'],
            ['key' => 'completed_analysis', 'label' => 'Analyses complétées', 'icon' => 'task_alt', 'tone' => 'success'],
            ['key' => 'total_entreprises', 'label' => 'Entreprises', 'icon' => 'storefront', 'href' => $this->routeIfExists('analyste.entreprises.index')],
            ['key' => 'pending_submission', 'label' => 'À soumettre à l\'exploitation', 'icon' => 'send', 'tone' => 'info'],
            ['key' => 'submitted_to_exploitation', 'label' => 'Déjà soumis', 'icon' => 'outbox'],
        ], fn () => $this->getStats()->getData(true), function () use ($dossierShow) {
            return collect($this->getTodos()->getData(true)['todos'] ?? [])->map(fn ($t) => [
                'token' => $t['token'],
                'entreprise' => $t['entreprise'],
                'programmes' => $t['programmes'],
                'when_human' => $t['assigned_human'] ? 'Affecté '.$t['assigned_human'] : null,
                'kind' => ['label' => 'À soumettre', 'variant' => 'warning'],
                'href' => $dossierShow($t['token']),
            ])->values()->all();
        }, [
            'subtitle' => 'Vue d\'ensemble de vos analyses financières.',
            'todosTitle' => 'Mes dossiers à soumettre (exploitation)',
            'todosLink' => ['label' => 'Voir tous', 'href' => $this->routeIfExists('analyste.dossiers.index')],
            'plainLinks' => true,
            'widgets' => [
                ['key' => 'alertes', 'type' => 'alerts', 'title' => 'Alertes', 'span' => 'full', 'empty' => 'Aucun dossier en retard.'],
                ['key' => 'distribution', 'type' => 'bars', 'title' => 'Distribution des dossiers', 'span' => 'half'],
                ['key' => 'mensuel', 'type' => 'columns', 'title' => 'Analyses mensuelles', 'span' => 'half',
                    'series' => [['label' => 'Dossiers analysés', 'tone' => 'success']]],
                ['key' => 'recents', 'type' => 'list', 'title' => 'Dossiers récents', 'span' => 'two-thirds', 'empty' => 'Aucun dossier.',
                    'link' => ['label' => 'Voir tous', 'href' => $this->routeIfExists('analyste.dossiers.index')]],
                ['key' => 'performance', 'type' => 'metrics', 'title' => 'Métriques de performance', 'span' => 'third'],
                ['key' => 'programmes', 'type' => 'list', 'title' => 'Programmes actifs', 'span' => 'half', 'empty' => 'Aucun programme.',
                    'link' => ['label' => 'Voir les programmes', 'href' => $this->routeIfExists('analyste.programmes.index')]],
                ['key' => 'raccourcis', 'type' => 'links', 'title' => 'Actions rapides', 'span' => 'half',
                    'items' => array_values(array_filter([
                        ['label' => 'Gérer les dossiers', 'icon' => 'folder_open', 'href' => $this->routeIfExists('analyste.dossiers.index')],
                        ['label' => 'Voir les entreprises', 'icon' => 'storefront', 'href' => $this->routeIfExists('analyste.entreprises.index')],
                        ['label' => 'Programmes', 'icon' => 'volunteer_activism', 'href' => $this->routeIfExists('analyste.programmes.index')],
                        ['label' => 'Voir les prospects', 'icon' => 'person_search', 'href' => $this->routeIfExists('analyste.entreprises.prospects')],
                    ], fn ($l) => $l['href'] !== null))],
            ],
            'widgetData' => function () use ($dossierShow) {
                $alerts = $this->getAlerts()->getData(true);
                $dist = $this->getDossiersDistribution()->getData(true);
                $monthly = $this->getMonthlyAnalysis()->getData(true);
                $perf = $this->getPerformanceMetrics()->getData(true);

                return [
                    'alertes' => array_values(array_filter([
                        ($alerts['urgent_dossiers'] ?? 0) > 0 ? ['tone' => 'warning', 'icon' => 'warning', 'title' => 'Attention',
                            'text' => $alerts['urgent_dossiers'].' dossier(s) en attente depuis plus de 3 jours.'] : null,
                        ($alerts['in_progress_dossiers'] ?? 0) > 0 ? ['tone' => 'info', 'icon' => 'autorenew', 'title' => 'En cours',
                            'text' => $alerts['in_progress_dossiers'].' dossier(s) en cours d\'analyse.'] : null,
                    ])),
                    'distribution' => [
                        ['label' => 'En cours', 'value' => $dist['en_cours'] ?? 0, 'tone' => 'info'],
                        ['label' => 'En attente', 'value' => $dist['en_attente'] ?? 0, 'tone' => 'warning'],
                        ['label' => 'Terminés', 'value' => $dist['termine'] ?? 0, 'tone' => 'success'],
                        ['label' => 'Rejetés', 'value' => $dist['rejete'] ?? 0, 'tone' => 'danger'],
                    ],
                    'mensuel' => collect($monthly['labels'] ?? [])->map(fn ($label, $i) => [
                        'label' => $label, 'values' => [$monthly['data'][$i] ?? 0],
                    ])->values()->all(),
                    'recents' => collect($this->getRecentDossiers()->getData(true))->map(fn ($d) => [
                        'title' => $d['entreprise_name'],
                        'subtitle' => $d['programme_name'],
                        'meta' => $d['updated_at'],
                        'badge' => ['label' => $d['statut_name'], 'variant' => (int) $d['statut'] === 1 ? 'info' : 'warning'],
                        'href' => $dossierShow($d['token']),
                    ])->values()->all(),
                    'performance' => [
                        ['label' => 'Taux de complétion', 'value' => ($perf['completion_rate'] ?? 0).' %', 'progress' => $perf['completion_rate'] ?? 0, 'tone' => 'success'],
                        ['label' => 'Complétés ce mois', 'value' => $perf['this_month_completed'] ?? 0],
                        ['label' => 'Total analysé', 'value' => $perf['total_analyzed'] ?? 0],
                        ['label' => 'En attente', 'value' => $perf['pending_count'] ?? 0],
                    ],
                    'programmes' => collect($this->getProgrammes()->getData(true))->map(fn ($p) => [
                        'title' => $p['name'],
                        'meta' => $p['dossiers_count'].' dossier(s)',
                    ])->values()->all(),
                ];
            },
        ]);
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
