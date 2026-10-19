<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        $prospects = $this->routeIfExists('gestionnaire.entreprises.prospects');
        $edit = fn (?string $token) => $token ? $this->routeIfExists('gestionnaire.entreprises.edit', $token) : null;
        $dossierShow = fn (?string $token) => $token ? $this->routeIfExists('gestionnaire.dossiers.show', $token) : null;

        return $this->renderInertiaDashboard('Tableau de bord gestionnaire', [
            ['key' => 'total_entreprises', 'label' => 'Entreprises', 'icon' => 'storefront', 'href' => $this->routeIfExists('gestionnaire.entreprises.index')],
            ['key' => 'total_dossiers', 'label' => 'Dossiers de l\'agence', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('gestionnaire.dossiers.index')],
            ['key' => 'total_prospects', 'label' => 'Prospects', 'icon' => 'person_search', 'href' => $prospects],
            ['key' => 'prospects_brouillon', 'label' => 'Prospects en brouillon', 'icon' => 'edit_note', 'href' => $prospects],
            ['key' => 'prospects_soumis', 'label' => 'Prospects soumis', 'icon' => 'send', 'href' => $prospects],
            ['key' => 'prospects_bloques_avis', 'label' => 'Bloqués (avis manquants)', 'icon' => 'hourglass_top', 'tone' => 'warning', 'href' => $prospects],
            ['key' => 'prospects_prets_arbitrage', 'label' => 'Prêts pour arbitrage', 'icon' => 'how_to_reg', 'tone' => 'info', 'href' => $prospects],
        ], fn () => $this->getStats()->getData(true), function () use ($edit) {
            $data = $this->getTodos()->getData(true);
            $drafts = collect($data['drafts'] ?? [])->map(fn ($p) => [
                'token' => $p['token'], 'entreprise' => $p['name'], 'programmes' => 'Prospect en brouillon — à compléter puis soumettre',
                'when_human' => $p['when_human'] ? 'Modifié '.$p['when_human'] : null,
                'kind' => ['label' => 'Brouillon', 'variant' => 'info'],
                'href' => $edit($p['token']),
            ]);
            $blocked = collect($data['blocked'] ?? [])->map(fn ($p) => [
                'token' => $p['token'], 'entreprise' => $p['name'], 'programmes' => 'En attente : '.($p['missing'] ?: 'avis'),
                'when_human' => $p['when_human'] ? 'Soumis '.$p['when_human'] : null,
                'kind' => ['label' => 'Avis manquants', 'variant' => 'warning'],
                'href' => $edit($p['token']),
            ]);

            return $drafts->concat($blocked)->values()->all();
        }, [
            'subtitle' => 'Vue d\'ensemble de vos activités.',
            'todosTitle' => 'Prospects à compléter ou en attente d\'avis',
            'todosLink' => $prospects ? ['label' => 'Ouvrir les prospects', 'href' => $prospects] : null,
            'plainLinks' => true,
            'widgets' => [
                ['key' => 'statuts', 'type' => 'bars', 'title' => 'Statut des dossiers', 'span' => 'half'],
                ['key' => 'entreprises', 'type' => 'columns', 'title' => 'Entreprises créées par mois', 'span' => 'half',
                    'series' => [['label' => 'Entreprises créées', 'tone' => 'success']]],
                ['key' => 'recents', 'type' => 'list', 'title' => 'Activités récentes', 'span' => 'two-thirds', 'empty' => 'Aucune activité récente.',
                    'link' => ['label' => 'Voir les dossiers', 'href' => $this->routeIfExists('gestionnaire.dossiers.index')]],
                ['key' => 'raccourcis', 'type' => 'links', 'title' => 'Actions rapides', 'span' => 'third',
                    'items' => array_values(array_filter([
                        ['label' => 'Créer un prospect', 'icon' => 'person_add', 'href' => $this->routeIfExists('gestionnaire.entreprises.prospects.create')],
                        ['label' => 'Ouvrir les prospects', 'icon' => 'person_search', 'href' => $prospects],
                        ['label' => 'Programmes', 'icon' => 'volunteer_activism', 'href' => $this->routeIfExists('gestionnaire.programmes.index')],
                    ], fn ($l) => $l['href'] !== null))],
            ],
            'widgetData' => function () use ($dossierShow) {
                $dist = $this->getDossiersDistribution()->getData(true);
                $ent = $this->getEntreprisesData()->getData(true);
                $variants = [0 => 'warning', 1 => 'info', 2 => 'success', 3 => 'danger'];

                return [
                    'statuts' => [
                        ['label' => 'En cours', 'value' => $dist['en_cours'] ?? 0, 'tone' => 'info'],
                        ['label' => 'En attente', 'value' => $dist['en_attente'] ?? 0, 'tone' => 'warning'],
                        ['label' => 'Terminés', 'value' => $dist['termine'] ?? 0, 'tone' => 'success'],
                        ['label' => 'Rejetés', 'value' => $dist['rejete'] ?? 0, 'tone' => 'danger'],
                    ],
                    'entreprises' => collect($ent['labels'] ?? [])->map(fn ($label, $i) => [
                        'label' => $label, 'values' => [$ent['data'][$i] ?? 0],
                    ])->values()->all(),
                    'recents' => collect($this->getRecentDossiers()->getData(true))->map(fn ($d) => [
                        'title' => $d['entreprise_name'],
                        'subtitle' => $d['programme_name'],
                        'meta' => $d['updated_at'],
                        'badge' => ['label' => $d['statut_name'] ?: 'En cours', 'variant' => $variants[(int) $d['statut']] ?? 'neutral'],
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
