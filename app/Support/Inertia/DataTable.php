<?php

namespace App\Support\Inertia;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

/**
 * Description d'un tableau paginé côté serveur pour le composant Vue `DataTable`.
 *
 * Point d'évolution unique des listes : colonnes, filtres et exports sont déclarés ici par le
 * contrôleur ; le composant Vue est générique (filtres en query string, pagination Inertia).
 *
 * Types de cellule : text (défaut), strong, badge {label, variant}, number, muted.
 */
final class DataTable
{
    private array $columns = [];

    private array $filters = [];

    private array $exports = [];

    private ?Closure $row = null;

    private string $empty = 'Aucune donnée.';

    private ?string $search = null;

    public function __construct(private readonly LengthAwarePaginator $paginator, private readonly Request $request)
    {
    }

    public static function make(LengthAwarePaginator $paginator, ?Request $request = null): self
    {
        return new self($paginator, $request ?? request());
    }

    /**
     * @param  array<int, array{key: string, label: string, align?: string, width?: string}>  $columns
     */
    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Transforme un élément en ligne : ['cells' => [key => valeur], 'href' => ?string].
     */
    public function row(Closure $row): self
    {
        $this->row = $row;

        return $this;
    }

    /** Champ de recherche libre (paramètre `q` par défaut). */
    public function search(string $placeholder, string $name = 'q'): self
    {
        $this->search = $placeholder;
        $this->filters[] = ['name' => $name, 'label' => 'Recherche', 'type' => 'search', 'placeholder' => $placeholder,
            'value' => (string) $this->request->query($name, '')];

        return $this;
    }

    /**
     * @param  array<int|string, string>|iterable  $options  valeur => libellé
     */
    public function select(string $name, string $label, iterable $options, string $allLabel = 'Tous'): self
    {
        $opts = [];
        foreach ($options as $value => $text) {
            $opts[] = ['value' => (string) $value, 'label' => (string) $text];
        }
        $this->filters[] = ['name' => $name, 'label' => $label, 'type' => 'select', 'allLabel' => $allLabel,
            'options' => $opts, 'value' => (string) $this->request->query($name, '')];

        return $this;
    }

    public function date(string $name, string $label): self
    {
        $this->filters[] = ['name' => $name, 'label' => $label, 'type' => 'date',
            'value' => (string) $this->request->query($name, '')];

        return $this;
    }

    /** Lien d'export : les filtres courants sont transmis automatiquement. */
    public function export(string $label, string $url, string $icon = 'download'): self
    {
        $query = array_filter($this->request->except(['page', 'format']), fn ($v) => $v !== null && $v !== '');
        $sep = str_contains($url, '?') ? '&' : '?';
        $this->exports[] = ['label' => $label, 'icon' => $icon, 'url' => $query ? $url.$sep.http_build_query($query) : $url];

        return $this;
    }

    public function empty(string $text): self
    {
        $this->empty = $text;

        return $this;
    }

    public function toArray(): array
    {
        $row = $this->row ?? fn ($item) => ['cells' => (array) $item, 'href' => null];
        $p = $this->paginator;

        return [
            'columns' => $this->columns,
            'rows' => collect($p->items())->map($row)->values()->all(),
            'filters' => $this->filters,
            'exports' => $this->exports,
            'empty' => $this->empty,
            'pagination' => [
                'total' => $p->total(),
                'from' => $p->firstItem(),
                'to' => $p->lastItem(),
                'currentPage' => $p->currentPage(),
                'lastPage' => $p->lastPage(),
                'prev' => $p->previousPageUrl(),
                'next' => $p->nextPageUrl(),
                'pages' => collect($p->getUrlRange(max(1, $p->currentPage() - 2), min($p->lastPage(), $p->currentPage() + 2)))
                    ->map(fn ($url, $page) => ['page' => $page, 'url' => $url, 'active' => $page === $p->currentPage()])
                    ->values()->all(),
            ],
            'baseUrl' => $this->request->url(),
        ];
    }
}
