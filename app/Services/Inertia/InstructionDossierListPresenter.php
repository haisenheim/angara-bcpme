<?php

namespace App\Services\Inertia;

use App\Models\Dossier;
use App\Models\Programme;
use App\Models\User;
use App\Support\Inertia\Badge;
use App\Support\Inertia\DataTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Listes « Dossiers d'instruction » des espaces agence / analyste / administration (page Inertia Dossiers/Index).
 *
 * Le périmètre et les filtres restent dans chaque contrôleur (mêmes requêtes que les anciens endpoints AJAX
 * fetchPaginated / fetchStats / fetchFilterOptions) ; ce presenter ne fait que la mise en forme du tableau.
 */
class InstructionDossierListPresenter
{
    public const RELATIONS = ['entreprise.produit', 'programme', 'instructionProgrammes.programme', 'analyste', 'gestionnaire', 'agence.representation'];

    /** Entités du filtre « Étape » (fetchFilterOptions). */
    public const ENTITIES = [
        'respexp' => 'Pôle exploitation',
        'juridique' => 'Pôle juridique',
        'reng' => 'Pôle engagements',
        'rerx' => 'Pôle risques',
        'dg' => 'Direction générale',
    ];

    /**
     * Recherche libre des endpoints fetchPaginated : entreprise, programme, programmes d'instruction, analyste.
     */
    public static function applySearch(Builder $query, string $search, bool $withGestionnaire = false): Builder
    {
        $search = trim($search);
        if ($search === '') {
            return $query;
        }

        return $query->where(function ($q) use ($search, $withGestionnaire) {
            $q->whereHas('entreprise', fn ($e) => $e->where('name', 'like', "%{$search}%"))
                ->orWhereHas('programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                ->orWhereHas('instructionProgrammes.programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                ->orWhereHas('analyste', fn ($a) => $a->where('name', 'like', "%{$search}%"));
            if ($withGestionnaire) {
                $q->orWhereHas('gestionnaire', fn ($g) => $g->where('name', 'like', "%{$search}%"));
            }
        });
    }

    /**
     * @param  Builder  $filtered  requête périmètre + filtres (sans la recherche), pour les indicateurs
     * @param  Builder  $scope  requête périmètre seule, pour les options du filtre analyste
     * @param  array{showRoute: string, title: string, subtitle: string, dashboard: string, columns?: string[], stats?: bool, filters?: string[], empty?: string}  $o
     */
    public function present(Request $request, Builder $filtered, Builder $scope, array $o): array
    {
        $stats = null;
        if ($o['stats'] ?? true) {
            $stats = [
                ['label' => 'Total dossiers', 'value' => (clone $filtered)->count(), 'icon' => 'folder', 'variant' => 'info'],
                ['label' => 'Avec analyste', 'value' => (clone $filtered)->whereNotNull('analyste_id')->count(), 'icon' => 'person_check', 'variant' => 'success'],
                ['label' => 'Sans analyste', 'value' => (clone $filtered)->whereNull('analyste_id')->count(), 'icon' => 'person_off', 'variant' => 'warning'],
            ];
        }

        $query = self::applySearch(clone $filtered, (string) $request->query('q', ''), in_array('gestionnaire', $o['columns'] ?? [], true));
        $paginator = $query->with(self::RELATIONS)->orderByDesc('created_at')->paginate(25)->withQueryString();

        $labels = [
            'programme' => 'Programme(s)',
            'signataire' => 'Signataire',
            'entreprise' => 'Entreprise',
            'gestionnaire' => 'Gestionnaire',
            'analyste' => 'Analyste',
            'agence' => 'Agence',
            'direction' => 'Direction',
            'secteur' => 'Secteur',
            'statut' => 'Statut',
            'etape' => 'Étape',
            'date' => 'Date',
        ];
        $keys = $o['columns'] ?? ['programme', 'signataire', 'entreprise', 'analyste', 'agence', 'secteur', 'statut', 'date'];
        $columns = array_map(fn ($k) => ['key' => $k, 'label' => $labels[$k]], $keys);

        $table = DataTable::make($paginator, $request)
            ->columns($columns)
            ->row(fn (Dossier $d) => [
                'href' => route($o['showRoute'], $d->token),
                'cells' => [
                    'programme' => $d->programmesLabel() ?: null,
                    'signataire' => $d->signatairesLabel() ?: null,
                    'entreprise' => ['title' => $d->entreprise?->name ?? '—', 'subtitle' => $d->currentOrganisationLabel() ?: null],
                    'gestionnaire' => $d->gestionnaire?->name,
                    'analyste' => $d->analyste?->name,
                    'agence' => $d->agence?->name,
                    'direction' => $d->agence?->representation?->name,
                    'secteur' => $d->entreprise?->produit?->name,
                    'statut' => Badge::fromPresentation($d->instructionStatutPresentation()),
                    'etape' => $d->currentOrganisationLabel() ?: null,
                    'date' => $d->created_at?->format('d/m/Y H:i'),
                ],
            ])
            ->search($o['searchPlaceholder'] ?? 'Entreprise, programme, analyste…')
            ->empty($o['empty'] ?? 'Aucun dossier d\'instruction.');

        $filters = $o['filters'] ?? ['programme_id', 'analyste_id', 'entity_route'];
        if (in_array('programme_id', $filters, true)) {
            $table->select('programme_id', 'Programme', Programme::query()->orderBy('name')->pluck('name', 'id'));
        }
        if (in_array('analyste_id', $filters, true)) {
            $ids = (clone $scope)->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
            $table->select('analyste_id', 'Analyste', User::query()->whereIn('id', $ids)->orderBy('name')->pluck('name', 'id'));
        }
        if (in_array('entity_route', $filters, true)) {
            $table->select('entity_route', 'Étape', self::ENTITIES, 'Toutes');
        }

        return [
            'title' => $o['title'],
            'subtitle' => $o['subtitle'],
            'stats' => $stats,
            'links' => $o['links'] ?? [],
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route($o['dashboard'])],
                ['label' => $o['title']],
            ],
        ];
    }
}
