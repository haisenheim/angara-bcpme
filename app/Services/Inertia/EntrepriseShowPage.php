<?php

namespace App\Services\Inertia;

use App\Http\Controllers\Concerns\BuildsEntrepriseQuestionnaireResults;
use App\Models\Entreprise;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/**
 * Fiche entreprise / prospect (page Inertia Entreprises/Show) des espaces agence, admin et analyste.
 *
 * Assemble EntrepriseSheetPresenter (contenu commun) et les liens / actions propres à chaque espace.
 * Les contrôles d'accès restent dans les contrôleurs appelants.
 */
class EntrepriseShowPage
{
    use BuildsEntrepriseQuestionnaireResults;

    public function __construct(private readonly EntrepriseSheetPresenter $presenter) {}

    /**
     * Contenu de la fiche (sans liens ni actions).
     *
     * @param  array<string, mixed>  $context  contexte EntrepriseSheetPresenter (routePrefix, dossierShowRoute…)
     */
    public function sheet(Entreprise $item, array $context): array
    {
        $item->loadMissing(EntrepriseSheetPresenter::RELATIONS);

        return $this->presenter->present($item, $this->buildQuestionnaireResults($item), $item->piecesExigiblesChecklist(), $context);
    }

    /**
     * Lien d'en-tête si la route existe : ['label', 'icon', 'href'].
     */
    public function link(string $route, mixed $params, string $label, string $icon): ?array
    {
        return Route::has($route) ? ['label' => $label, 'icon' => $icon, 'href' => route($route, $params)] : null;
    }

    public function simulationLink(Entreprise $item): ?array
    {
        return auth()->check() ? $this->link('simulator.index', ['entreprise_id' => $item->id], 'Simulation de crédit', 'calculate') : null;
    }

    /**
     * @param  array<int, ?array>  $links
     */
    public function render(Entreprise $item, string $space, array $sheet, array $links, array $extra = []): \Inertia\Response
    {
        $listRoute = $item->prospect && Route::has("$space.entreprises.prospects") ? "$space.entreprises.prospects" : "$space.entreprises.index";
        $listLabel = $item->prospect && Route::has("$space.entreprises.prospects") ? 'Prospects' : 'Entreprises';

        return \Inertia\Inertia::render('Entreprises/Show', $sheet + $extra + [
            'links' => array_values(array_filter($links)),
            'actions' => [],
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route("$space.dashboard")],
                ['label' => $listLabel, 'href' => route($listRoute)],
                ['label' => Str::limit((string) $item->name, 40)],
            ],
        ]);
    }
}
