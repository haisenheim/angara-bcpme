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

        $tree = $this->grid->buildTreeForEntreprise($entreprise->id);
        $stats = $this->grid->buildStatsForEntreprise($entreprise->id);
        $partenaires = $this->grid->partenairesGrouped();

        $produits = EngagementCategorie::query()
            ->leaves()
            ->orderBy('sort_order')
            ->get(['id', 'libelle', 'parent_id']);

        return view('engagements.index', [
            'workspaceLayout' => UserWorkspaceContextResolver::layout($user),
            'workspaceRoutePrefix' => UserWorkspaceContextResolver::routePrefix($user),
            'entreprise' => $entreprise,
            'tree' => $tree,
            'stats' => $stats,
            'partenaires' => $partenaires,
            'produits' => $produits,
            'statuts' => EngagementLigne::STATUTS,
            'kindLabels' => Banque::KIND_LABELS,
            'canEdit' => $canEdit,
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
