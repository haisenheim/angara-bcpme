<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\User;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        $dossierShow = fn (?string $token) => $token ? $this->routeIfExists('ca.dossiers.show', $token) : null;
        $prospectsQueue = $this->routeIfExists('ca.workflow.prospects.index');

        return $this->renderInertiaDashboard('Tableau de bord — chef d\'agence', [
            ['key' => 'total_dossiers', 'label' => 'Dossiers totaux', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('ca.dossiers.index')],
            ['key' => 'dossiers_en_cours', 'label' => 'Dossiers en cours', 'icon' => 'autorenew'],
            ['key' => 'total_entreprises', 'label' => 'Portefeuille', 'icon' => 'storefront', 'hint' => 'Entreprises de l\'agence', 'href' => $this->routeIfExists('ca.entreprises.index')],
            ['key' => 'total_users', 'label' => 'Équipe active', 'icon' => 'group', 'hint' => 'Utilisateurs de l\'agence', 'href' => $this->routeIfExists('ca.users.index')],
            ['key' => 'total_prospects', 'label' => 'Prospects actifs', 'icon' => 'person_search', 'hint' => 'En cours d\'entrée en relation', 'href' => $this->routeIfExists('ca.entreprises.prospects')],
            ['key' => 'workflow_prospects_submitted', 'label' => 'Prospects soumis', 'icon' => 'send', 'href' => $prospectsQueue],
            ['key' => 'workflow_prospects_blocked', 'label' => 'Prospects bloqués (avis)', 'icon' => 'hourglass_top', 'href' => $prospectsQueue],
            ['key' => 'workflow_prospects_pending', 'label' => 'Prospects prêts pour arbitrage', 'icon' => 'how_to_reg', 'tone' => 'warning', 'href' => $prospectsQueue],
            ['key' => 'workflow_eer_pending', 'label' => 'Structurations à valider', 'icon' => 'account_tree', 'tone' => 'warning',
                'href' => $this->routeIfExists('ca.workflow.instructions.index')],
            ['key' => 'workflow_instruction_bundles_pending', 'label' => 'Dossiers d\'instruction à valider', 'icon' => 'fact_check', 'tone' => 'warning',
                'href' => $this->routeIfExists('ca.workflow.instruction-dossiers.index')],
        ], fn () => $this->getStats()->getData(true), null, [
            'subtitle' => 'Vue d\'ensemble de votre agence et des décisions attendues.',
            'plainLinks' => true,
            'widgets' => [
                ['key' => 'alertes', 'type' => 'alerts', 'title' => 'Alertes & notifications', 'span' => 'full'],
                ['key' => 'performance', 'type' => 'columns', 'title' => 'Performance de l\'agence (6 derniers mois)', 'span' => 'two-thirds',
                    'series' => [['label' => 'Dossiers créés', 'tone' => 'success'], ['label' => 'Entreprises créées', 'tone' => 'brand']]],
                ['key' => 'mois', 'type' => 'metrics', 'title' => 'Statistiques du mois — '.now()->translatedFormat('F Y'), 'span' => 'third'],
                ['key' => 'equipe', 'type' => 'table', 'title' => 'Performance de l\'équipe', 'span' => 'half', 'empty' => 'Aucun membre actif.',
                    'link' => ['label' => 'Gérer', 'href' => $this->routeIfExists('ca.users.index')],
                    'columns' => [
                        ['key' => 'name', 'label' => 'Membre'],
                        ['key' => 'role', 'label' => 'Rôle'],
                        ['key' => 'dossiers', 'label' => 'Dossiers', 'align' => 'right'],
                        ['key' => 'statut', 'label' => 'Statut'],
                    ]],
                ['key' => 'recents', 'type' => 'list', 'title' => 'Dossiers récents', 'span' => 'half', 'empty' => 'Aucun dossier récent.',
                    'link' => ['label' => 'Voir tous', 'href' => $this->routeIfExists('ca.dossiers.index')]],
                ['key' => 'raccourcis', 'type' => 'links', 'title' => 'Actions rapides', 'span' => 'full',
                    'items' => array_values(array_filter([
                        ['label' => 'File d\'arbitrage des prospects', 'icon' => 'how_to_reg', 'href' => $prospectsQueue],
                        ['label' => 'Validations de structuration', 'icon' => 'account_tree', 'href' => $this->routeIfExists('ca.workflow.instructions.index')],
                        ['label' => 'Dossiers d\'instruction (multi-programmes)', 'icon' => 'fact_check', 'href' => $this->routeIfExists('ca.workflow.instruction-dossiers.index')],
                        ['label' => 'Gérer les entreprises', 'icon' => 'storefront', 'href' => $this->routeIfExists('ca.entreprises.index')],
                        ['label' => 'Suivre les dossiers', 'icon' => 'folder_open', 'href' => $this->routeIfExists('ca.dossiers.index')],
                        ['label' => 'Gérer l\'équipe', 'icon' => 'group', 'href' => $this->routeIfExists('ca.users.index')],
                        ['label' => 'Voir les programmes', 'icon' => 'volunteer_activism', 'href' => $this->routeIfExists('ca.programmes.index')],
                    ], fn ($l) => $l['href'] !== null))],
            ],
            'widgetData' => function () use ($dossierShow) {
                $perf = $this->getPerformanceData()->getData(true);
                $month = $this->getMonthlyStats()->getData(true);
                $alerts = $this->getAlerts()->getData(true);
                $variants = [0 => 'warning', 1 => 'info', 2 => 'success', 3 => 'danger'];

                return [
                    'alertes' => array_values(array_filter([
                        ($alerts['pending_dossiers'] ?? 0) > 0 ? ['tone' => 'warning', 'icon' => 'schedule', 'title' => 'Dossiers en attente',
                            'text' => $alerts['pending_dossiers'].' dossier(s) en attente de traitement.'] : null,
                        ($alerts['old_pending'] ?? 0) > 0 ? ['tone' => 'danger', 'icon' => 'warning', 'title' => 'Attention requise',
                            'text' => $alerts['old_pending'].' dossier(s) en attente depuis plus de 7 jours.'] : null,
                        ($alerts['inactive_users'] ?? 0) > 0 ? ['tone' => 'info', 'icon' => 'person_off', 'title' => 'Utilisateurs inactifs',
                            'text' => $alerts['inactive_users'].' compte(s) désactivé(s).'] : null,
                    ])),
                    'performance' => collect($perf['labels'] ?? [])->map(fn ($label, $i) => [
                        'label' => $label,
                        'values' => [$perf['dossiers'][$i] ?? 0, $perf['entreprises'][$i] ?? 0],
                    ])->values()->all(),
                    'mois' => [
                        ['label' => 'Nouveaux dossiers', 'value' => $month['new_dossiers'] ?? 0],
                        ['label' => 'Nouvelles entreprises', 'value' => $month['new_entreprises'] ?? 0],
                        ['label' => 'Dossiers complétés', 'value' => $month['completed_dossiers'] ?? 0],
                        ['label' => 'Prospects actifs', 'value' => $month['active_prospects'] ?? 0],
                    ],
                    'equipe' => ['rows' => collect($this->getTeamPerformance()->getData(true))->map(fn ($m) => ['cells' => [
                        'name' => $m['name'],
                        'role' => $m['role'],
                        'dossiers' => $m['dossiers_count'],
                        'statut' => ['badge' => $m['active'] ? 'Actif' : 'Inactif', 'variant' => $m['active'] ? 'success' : 'neutral'],
                    ]])->values()->all()],
                    'recents' => collect($this->getRecentDossiers()->getData(true))->map(fn ($d) => [
                        'title' => $d['entreprise_name'],
                        'subtitle' => $d['programme_name'].' · '.$d['gestionnaire_name'],
                        'meta' => $d['updated_at'],
                        'badge' => ['label' => $d['statut_name'], 'variant' => $variants[(int) $d['statut']] ?? 'neutral'],
                        'href' => $dossierShow($d['token']),
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
        $agenceId = auth()->user()->agence_id;

        // Get dossiers with indicateurs (en cours)
        $dossiersEnCours = Dossier::where('agence_id', $agenceId)
            ->whereHas('indicateurs')
            ->count();
        $users = User::where('agence_id', $agenceId)->where('active', 1)->get();

        $stats = [
            'total_dossiers' => Dossier::where('agence_id', $agenceId)->count(),
            'dossiers_en_cours' => $dossiersEnCours,
            'total_entreprises' => Entreprise::where('agence_id', $agenceId)->count(),
            'total_cooperatives' => 0,
            'total_users' => $users->count(),
            'total_prospects' => Entreprise::where('agence_id', $agenceId)
                ->where('prospect', 1)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
            // Prospects soumis (tous) : visibilité "activité" (pas forcément prêts décision).
            'workflow_prospects_submitted' => Entreprise::query()
                ->where('prospect', true)
                ->where('agence_id', $agenceId)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->count(),
            // Prospects "bloqués" : soumis, non décidés, avis juridique et/ou conformité manquant.
            'workflow_prospects_blocked' => Entreprise::query()
                ->where('prospect', true)
                ->where('agence_id', $agenceId)
                ->whereNotNull('prospect_submitted_at')
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->where(function ($q) {
                    $q->whereNull('juridique_avis_at')
                        ->orWhereNull('conformite_avis_at');
                })
                ->count(),
            'workflow_prospects_pending' => Entreprise::query()
                ->where('prospect', true)
                ->where('agence_id', $agenceId)
                ->whereNotNull('prospect_submitted_at')
                // Circuit d'avis en parallèle: décision chef d'agence uniquement après avis juridique + conformité.
                ->whereNotNull('juridique_avis_at')
                ->whereNotNull('conformite_avis_at')
                // Non encore décidé (ni promu client, ni rejeté).
                ->whereNull('promu_client_at')
                ->whereNull('prospect_rejected_at')
                ->count(),
            'workflow_eer_pending' => DossierEntreeRelation::query()
                ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
                ->whereNull('qualification_validated_by_agence_at')
                ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
                ->count(),
            'workflow_instruction_bundles_pending' => Dossier::on('central_app_mysql')
                ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
                ->whereNotNull('chef_filiere_submitted_to_agence_at')
                ->whereNull('instruction_agence_validated_at')
                ->whereNull('instruction_agence_rejected_at')
                ->whereHas('instructionProgrammes')
                ->count(),
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
            ->map(function ($member) {
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
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme', 'gestionnaire'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($dossier) {
                $status = $dossier->status;

                return [
                    'id' => $dossier->id,
                    'token' => $dossier->token,
                    'entreprise_name' => $dossier->entreprise->name ?? 'N/A',
                    'programme_name' => $dossier->programmesLabel(),
                    'gestionnaire_name' => $dossier->gestionnaire->name ?? 'Non assigné',
                    'statut' => $status['code'],
                    'statut_name' => $status['name'],
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
                ->whereHas('indicateurs')
                ->whereMonth('updated_at', now()->month)
                ->count(),
            'active_prospects' => Entreprise::where('agence_id', $agenceId)
                ->where('prospect', 1)
                ->whereNotNull('prospect_submitted_at')
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

        // Dossiers without indicateurs are "en attente"
        $pendingDossiers = Dossier::where('agence_id', $agenceId)
            ->whereDoesntHave('indicateurs')
            ->count();

        $oldPending = Dossier::where('agence_id', $agenceId)
            ->whereDoesntHave('indicateurs')
            ->where('created_at', '<=', now()->subDays(7))
            ->count();

        $alerts = [
            'pending_dossiers' => $pendingDossiers,
            'old_pending' => $oldPending,
            'inactive_users' => User::where('agence_id', $agenceId)
                ->where('active', 0)
                ->count(),
        ];

        return response()->json($alerts);
    }
}
