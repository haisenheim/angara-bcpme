<?php

namespace App\Http\Controllers\Engagement;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Engagement\EngagementCategorie;
use App\Models\Engagement\EngagementLigne;
use App\Models\Entreprise;
use App\Services\Engagement\EngagementAccessService;
use App\Services\Engagement\EngagementExportService;
use App\Services\Engagement\EngagementGridService;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/**
 * Contrôleur unique pour la fonctionnalité « Grille des engagements ».
 *
 * Le même endpoint sert toutes les espaces de rôle ; le contexte (lecture
 * ou édition) est résolu via {@see EngagementAccessService} et le layout
 * d'affichage par {@see ResolvesRoleSpace}.
 */
class EngagementController extends Controller
{
    public function __construct(
        private readonly EngagementGridService $grid,
        private readonly EngagementAccessService $access,
        private readonly EngagementExportService $exporter,
    ) {}

    /**
     * Page principale (vue Excel-style).
     */
    public function show(Request $request, string $token)
    {
        $user = auth()->user();

        if (! $this->access->userPeutConsulter($user)) {
            abort(403);
        }

        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $canEdit = $this->access->userPeutEcrire($user, $entreprise);

        // Filtres (mêmes paramètres que l'endpoint JSON `data`) : la page Inertia se recharge avec eux.
        $partenaireId = $request->integer('partenaire_id') ?: null;
        $search = trim((string) $request->query('search', ''));

        $tree = $this->grid->buildTreeForEntreprise($entreprise->id, $partenaireId, $search);
        $stats = $this->grid->buildStatsForEntreprise($entreprise->id, $partenaireId, $search);
        $partenaires = $this->grid->partenairesGrouped();

        $produits = EngagementCategorie::query()
            ->leaves()
            ->orderBy('sort_order')
            ->get(['id', 'libelle', 'parent_id']);

        $prefix = UserWorkspaceContextResolver::routePrefix($user);
        $breadcrumb = [];
        if ($prefix && Route::has($prefix.'.dashboard')) {
            $breadcrumb[] = ['label' => 'Tableau de bord', 'href' => route($prefix.'.dashboard')];
        }
        if ($prefix && Route::has($prefix.'.entreprises.index')) {
            $breadcrumb[] = ['label' => in_array($prefix, ['dg', 'dga'], true) ? 'Clients' : 'Entreprises', 'href' => route($prefix.'.entreprises.index')];
        }
        $breadcrumb[] = ['label' => 'Grille des engagements'];

        $group = fn (array $items) => collect($items)->map(fn ($p) => ['value' => (string) $p->id, 'label' => $p->name])->values()->all();
        $money = fn ($v) => number_format((float) $v, 0, ',', ' ');

        return Inertia::render('Engagements/Show', [
            'entreprise' => ['name' => $entreprise->name, 'niu' => $entreprise->niu, 'token' => $entreprise->token],
            'stats' => [
                ['key' => 'encours_actuel', 'label' => 'Encours actuel', 'value' => $money($stats['encours_actuel']), 'unit' => 'XAF'],
                ['key' => 'sollicite_montant', 'label' => 'Sollicité', 'value' => $money($stats['sollicite_montant']), 'unit' => 'XAF'],
                ['key' => 'total_montant', 'label' => 'Total engagement', 'value' => $money($stats['total_montant']), 'unit' => 'XAF', 'tone' => 'primary'],
                ['key' => 'variation', 'label' => 'Variation', 'value' => $money($stats['variation']), 'unit' => 'XAF'],
                ['key' => 'encours_impayes', 'label' => 'Impayés', 'value' => $money($stats['encours_impayes']), 'unit' => 'XAF', 'tone' => 'danger'],
                ['key' => 'nb_lignes', 'label' => 'Lignes saisies', 'value' => (string) $stats['nb_lignes'], 'unit' => 'partenaires confondus'],
            ],
            'tree' => $tree,
            'filters' => ['partenaire_id' => $partenaireId ? (string) $partenaireId : '', 'search' => $search],
            'partenaireGroups' => array_values(array_filter([
                ['label' => 'Banques', 'options' => $group($partenaires['banques'])],
                ['label' => 'EMF', 'options' => $group($partenaires['emfs'])],
                ['label' => 'Autres partenaires', 'options' => $group($partenaires['autres'])],
            ], fn ($g) => count($g['options']) > 0)),
            'produits' => $produits->map(fn ($p) => ['value' => (string) $p->id, 'label' => $p->libelle])->values()->all(),
            'statuts' => collect(EngagementLigne::STATUTS)->map(fn ($label, $code) => ['value' => $code, 'label' => $label])->values()->all(),
            'kindLabels' => collect(Banque::KIND_LABELS)->map(fn ($label, $code) => ['value' => $code, 'label' => $label])->values()->all(),
            'canEdit' => $canEdit,
            'reports' => collect(config('engagements_exports.reports', []))->map(fn ($r) => [
                'id' => $r['id'], 'label' => $r['label'], 'description' => $r['description'] ?? null,
                'enabled' => ! empty($r['enabled']), 'pdf' => ! empty($r['pdf']), 'xlsx' => ! empty($r['xlsx']),
            ])->values()->all(),
            'endpoints' => [
                'show' => route('engagements.show', $entreprise->token),
                'export' => route('engagements.export', $entreprise->token),
                'partenaireStore' => route('engagements.partenaires.store'),
                'ligneStore' => $canEdit ? route('engagements.lignes.store', $entreprise->token) : null,
                'ligneUpdate' => $canEdit ? route('engagements.lignes.update', ['token' => $entreprise->token, 'ligne' => '__ID__']) : null,
                'ligneDestroy' => $canEdit ? route('engagements.lignes.destroy', ['token' => $entreprise->token, 'ligne' => '__ID__']) : null,
            ],
            'breadcrumb' => $breadcrumb,
        ]);
    }

    /**
     * Endpoint JSON pour rafraîchir la grille (filtres dynamiques).
     */
    public function data(Request $request, string $token): JsonResponse
    {
        if (! $this->access->userPeutConsulter(auth()->user())) {
            abort(403);
        }

        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $partenaireId = $request->integer('partenaire_id') ?: null;
        $search = trim((string) $request->query('search', ''));

        $tree = $this->grid->buildTreeForEntreprise($entreprise->id, $partenaireId, $search);
        $stats = $this->grid->buildStatsForEntreprise($entreprise->id, $partenaireId, $search);

        return response()->json([
            'tree' => $tree,
            'stats' => $stats,
        ]);
    }

    /**
     * Export PDF / Excel — rendu hiérarchique fidèle au modèle Excel d'origine.
     */
    public function export(Request $request, string $token)
    {
        if (! $this->access->userPeutConsulter(auth()->user())) {
            abort(403);
        }

        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide.');
        }

        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $partenaireId = $request->integer('partenaire_id') ?: null;
        $search = trim((string) $request->query('search', ''));
        $reportId = trim((string) $request->query('report', 'grille_complete'));

        return $this->exporter->download($entreprise, $format, $partenaireId, $search, $reportId);
    }

    /**
     * Redirection rétro-compat depuis les anciennes routes
     * `{role}.entreprise.get.engagements`.
     */
    public function redirectLegacy(string $token): RedirectResponse
    {
        $user = auth()->user();

        if (! $this->access->userPeutConsulter($user)) {
            abort(403);
        }

        return redirect()->route('engagements.show', $token);
    }
}
