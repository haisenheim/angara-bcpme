<?php

namespace App\Services\Inertia;

use App\Models\Agence;
use App\Models\Departement;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Region;
use App\Models\User;
use App\Support\Inertia\Badge;
use App\Support\Inertia\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Listes paginées côté serveur des entreprises / clients / prospects des espaces agence, admin
 * et analyste (page Inertia Entreprises/Index).
 *
 * Le périmètre et les filtres métier sont appliqués par le contrôleur appelant, avec les mêmes
 * requêtes que ses endpoints AJAX historiques (fetchPaginated, fetchProspectsPaginated…) :
 * ce presenter ne fait que la recherche libre, la pagination, la mise en forme et la déclaration
 * des filtres affichés.
 */
class EntrepriseListPresenter
{
    /** Valeurs de la colonne `taille` (listes déroulantes historiques). */
    public const TAILLES = [
        'GRANDE' => 'Grande',
        'MOYENNE' => 'Moyenne',
        'PETITE' => 'Petite',
        'TRES PETITE' => 'Très petite',
        'COOPERATIVE' => 'Coopérative',
        'ASSOCIATION' => 'Association',
    ];

    public const CARACTERES = ['Formel' => 'Formel', 'Informel' => 'Informel'];

    public const SUBMISSIONS = ['draft' => 'Brouillon', 'submitted' => 'Soumis'];

    /** Colonnes de recherche libre des endpoints AJAX (search.value). */
    public const SEARCH_COLUMNS = ['name', 'rccm', 'niu', 'manager', 'email', 'phone'];

    private const COLUMNS = [
        'entreprise' => ['key' => 'entreprise', 'label' => 'Dénomination'],
        'identifiants' => ['key' => 'identifiants', 'label' => 'RCCM / NIU'],
        'dirigeant' => ['key' => 'dirigeant', 'label' => 'Dirigeant'],
        'localisation' => ['key' => 'localisation', 'label' => 'Localisation'],
        'region' => ['key' => 'region', 'label' => 'Région'],
        'forme' => ['key' => 'forme', 'label' => 'Forme'],
        'taille' => ['key' => 'taille', 'label' => 'Taille'],
        'capital' => ['key' => 'capital', 'label' => 'Capital', 'align' => 'right'],
        'agence' => ['key' => 'agence', 'label' => 'Agence'],
        'gestionnaire' => ['key' => 'gestionnaire', 'label' => 'Gestionnaire'],
        'promu' => ['key' => 'promu', 'label' => 'Promu client'],
        'statut' => ['key' => 'statut', 'label' => 'Statut'],
        'structuration' => ['key' => 'structuration', 'label' => 'Structuration'],
        'soumis' => ['key' => 'soumis', 'label' => 'Soumis le'],
    ];

    /**
     * Recherche libre identique aux endpoints AJAX (`search.value`).
     *
     * @param  Builder<Entreprise>  $query
     * @param  array<int, string>  $columns
     */
    public function applySearch(Builder $query, ?string $search, array $columns = self::SEARCH_COLUMNS): Builder
    {
        $search = trim((string) $search);
        if ($search === '') {
            return $query;
        }

        return $query->where(function ($q) use ($search, $columns) {
            foreach ($columns as $i => $col) {
                $i === 0 ? $q->where($col, 'like', "%{$search}%") : $q->orWhere($col, 'like', "%{$search}%");
            }
        });
    }

    /**
     * Construit les props de la page Entreprises/Index.
     *
     * @param  Builder<Entreprise>  $query  périmètre + filtres déjà appliqués (hors recherche libre)
     * @param  array{
     *     title: string, subtitle?: ?string, countLabel?: string, showRoute: string, columns: array<int, string>,
     *     filters?: array<int, string|array>, searchColumns?: array<int, string>, orderBy?: array{0: string, 1: string},
     *     exportRoute?: ?string, exportSearch?: bool, stats?: array, actions?: array, breadcrumb: array, empty?: string,
     *     agences?: iterable, gestionnaires?: iterable, perPage?: int
     * }  $o
     */
    public function render(Request $request, Builder $query, array $o): \Inertia\Response
    {
        return \Inertia\Inertia::render('Entreprises/Index', $this->props($request, $query, $o));
    }

    public function props(Request $request, Builder $query, array $o): array
    {
        $withSearch = $o['search'] ?? true;
        $search = $withSearch ? (string) $request->query('q', '') : '';
        $this->applySearch($query, $search, $o['searchColumns'] ?? self::SEARCH_COLUMNS);

        [$orderCol, $orderDir] = $o['orderBy'] ?? ['created_at', 'desc'];
        $items = $query
            ->with(['forme', 'arrondissement', 'region', 'agence', 'gestionnaire', 'user', 'dossierEntreeRelation'])
            ->orderBy($orderCol, $orderDir)
            ->orderByDesc('id')
            ->paginate($o['perPage'] ?? 25)
            ->withQueryString();

        $columns = array_values(array_map(fn ($k) => self::COLUMNS[$k], $o['columns']));
        $showRoute = $o['showRoute'];

        $table = DataTable::make($items, $request)
            ->columns($columns)
            ->row(fn (Entreprise $e) => [
                'href' => route($showRoute, $e->token),
                'cells' => $this->cells($e, $o['columns']),
            ])
            ->empty($o['empty'] ?? 'Aucune entreprise trouvée.');

        if ($withSearch) {
            $table->search('Nom, RCCM, NIU, dirigeant…');
        }
        foreach ($o['filters'] ?? [] as $filter) {
            $this->declareFilter($table, $filter, $o);
        }

        if (! empty($o['exportRoute'])) {
            // Les exports historiques lisent la recherche dans `search[value]`.
            $extra = ($o['exportSearch'] ?? true) && trim($search) !== '' ? ['search' => ['value' => $search]] : [];
            $table->export('Excel', route($o['exportRoute'], ['format' => 'xlsx'] + $extra), 'table_view')
                ->export('PDF', route($o['exportRoute'], ['format' => 'pdf'] + $extra), 'picture_as_pdf');
        }

        return [
            'title' => $o['title'],
            'subtitle' => $o['subtitle'] ?? null,
            'countLabel' => $o['countLabel'] ?? 'entreprise(s)',
            'table' => $table->toArray(),
            'stats' => $o['stats'] ?? [],
            'actions' => $o['actions'] ?? [],
            'breadcrumb' => $o['breadcrumb'],
        ];
    }

    /**
     * Indicateurs des pages prospects / clients (mêmes clés que fetchStats / fetchProspectsStats).
     *
     * @param  array{total?: int, formelles?: int, informelles?: int, par_taille?: array}  $stats
     */
    public function standardStats(array $stats, string $totalLabel): array
    {
        $parTaille = $stats['par_taille'] ?? [];

        return [
            ['label' => $totalLabel, 'value' => (int) ($stats['total'] ?? 0), 'icon' => 'domain'],
            ['label' => 'Formelles', 'value' => (int) ($stats['formelles'] ?? 0), 'icon' => 'verified'],
            ['label' => 'Informelles', 'value' => (int) ($stats['informelles'] ?? 0), 'icon' => 'storefront'],
            ['label' => 'Petites / TPE', 'value' => (int) ($parTaille['PETITE'] ?? 0) + (int) ($parTaille['TRES PETITE'] ?? 0), 'icon' => 'groups'],
        ];
    }

    /**
     * Options agences / gestionnaires présentes dans un périmètre (mêmes requêtes que fetch*FilterOptions).
     *
     * @param  Builder<Entreprise>  $scope
     * @return array{agences: \Illuminate\Support\Collection, gestionnaires: \Illuminate\Support\Collection}
     */
    public function agenceGestionnaireOptions(Builder $scope): array
    {
        $agenceIds = (clone $scope)->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = (clone $scope)->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');

        return [
            'agences' => Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->pluck('name', 'id'),
            'gestionnaires' => User::query()->whereIn('id', $gestionnaireIds)->orderBy('name')->pluck('name', 'id'),
        ];
    }

    private function declareFilter(DataTable $table, string|array $filter, array $o): void
    {
        match ($filter) {
            'region_id' => $table->select('region_id', 'Région', Region::query()->orderBy('name')->pluck('name', 'id'), 'Toutes'),
            'departement_id' => $table->select('departement_id', 'Département', Departement::query()->orderBy('name')->pluck('name', 'id'), 'Tous'),
            'taille' => $table->select('taille', 'Taille', self::TAILLES, 'Toutes'),
            'forme_id' => $table->select('forme_id', 'Forme juridique', Forme::query()->orderBy('name')->pluck('name', 'id'), 'Toutes'),
            'caractere' => $table->select('caractere', 'Caractère', self::CARACTERES),
            'submission' => $table->select('submission', 'Soumission', self::SUBMISSIONS),
            'client_structuration_status' => $table->select('client_structuration_status', 'Structuration', DossierEntreeRelation::clientStructurationStatusFilterLabels()),
            'agence_id' => $table->select('agence_id', 'Agence', $o['agences'] ?? [], 'Toutes'),
            'gestionnaire_id' => $table->select('gestionnaire_id', 'Gestionnaire', $o['gestionnaires'] ?? []),
            'promu_client' => $table->date('promu_client_from', 'Promu client du')->date('promu_client_to', 'au'),
            'created' => $table->date('created_from', 'Création du')->date('created_to', 'au'),
            default => null,
        };
    }

    private function cells(Entreprise $e, array $keys): array
    {
        $cells = [];
        foreach ($keys as $key) {
            $cells[$key] = match ($key) {
                'entreprise' => ['title' => $e->name ?: '—', 'subtitle' => $e->forme?->name],
                'identifiants' => ($e->rccm || $e->niu) ? ['title' => $e->rccm ?: '—', 'subtitle' => $e->niu ? 'NIU '.$e->niu : null] : null,
                'dirigeant' => $e->manager,
                'localisation' => $e->arrondissement || $e->region
                    ? trim(($e->arrondissement?->name ?? '—').($e->region ? ' / '.$e->region->name : ''))
                    : null,
                'region' => $e->region?->name,
                'forme' => $e->forme?->name,
                'taille' => $e->taille ? ['badge' => self::TAILLES[$e->taille] ?? $e->taille, 'variant' => 'neutral'] : null,
                'capital' => ($e->capital !== null && $e->capital !== '') ? number_format((float) $e->capital, 0, ',', ' ').' FCFA' : null,
                'agence' => $e->agence?->name,
                'gestionnaire' => $e->gestionnaire?->name ?? $e->user?->name,
                'promu' => $e->promu_client_at ? \Carbon\Carbon::parse($e->promu_client_at)->format('d/m/Y H:i') : null,
                'statut' => Badge::fromPresentation($e->clientStatutPresentation()),
                'structuration' => $e->promu_client_at
                    ? Badge::fromPresentation(DossierEntreeRelation::clientStructurationPresentation($e->dossierEntreeRelation))
                    : null,
                'soumis' => $e->prospect_submitted_at ? \Carbon\Carbon::parse($e->prospect_submitted_at)->format('d/m/Y H:i') : null,
                default => null,
            };
        }

        return $cells;
    }
}
