<?php

namespace App\Http\Controllers\RoleSpace;

use App\Models\User;
use App\Services\Inertia\DossierInstructionPagePresenter;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Fiche dossier d'instruction — version Inertia (refonte).
 *
 * Hérite de PortfolioController pour réutiliser à l'identique le périmètre d'accès par espace
 * (dossierQueryForCurrentSpace) ; les actions POST restent celles des routes existantes.
 */
class DossierPageController extends PortfolioController
{
    /** Espace responsable → profil des analystes qu'il peut affecter. */
    private const ASSIGNABLE_ANALYSTS = [
        'respexp' => ['role_analyste_financier', 17],
        'juridique' => ['role_analyste_juridique', 19],
        'reng' => ['role_analyste_credit', 20],
        'rerx' => ['role_analyste_risques', 18],
    ];

    public function show(string $token, DossierInstructionPagePresenter $presenter): Response
    {
        $space = $this->resolveSpace();
        $route = $space['route'];

        $dossier = $this->dossierQueryForCurrentSpace($token)
            ->with(DossierInstructionPagePresenter::RELATIONS)
            ->firstOrFail();

        $readonly = in_array($route, ['gestionnaire', 'conformite'], true);

        $analystes = [];
        if (isset(self::ASSIGNABLE_ANALYSTS[$route])) {
            [$key, $default] = self::ASSIGNABLE_ANALYSTS[$route];
            $analystes[$route] = User::query()
                ->where('role_id', (int) (config('angara.'.$key) ?: $default))
                ->where(fn ($q) => $q->where('active', true)->orWhereNull('active'))
                ->with('agence:id,name')
                ->orderBy('name')
                ->get(['id', 'name', 'agence_id']);
        }

        $legacyRoute = "$route.dossiers.show";
        $legacyUrl = Route::has($legacyRoute) ? route($legacyRoute, $dossier->token) : url()->previous();

        $props = $presenter->present($dossier, $space, auth()->user(), $readonly, $analystes, $legacyUrl);
        $props['breadcrumb'] = [
            ['label' => 'Dossiers d\'instruction', 'href' => $props['links']['list'] ?? null],
            ['label' => $props['dossier']['reference']],
        ];

        return Inertia::render('Dossiers/Show', $props);
    }
}
