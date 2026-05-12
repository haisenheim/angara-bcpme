<?php

namespace App\Services\Engagement;

use App\Models\Banque;
use App\Models\Engagement\EngagementCategorie;
use App\Models\Engagement\EngagementLigne;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

/**
 * Service central pour l'affichage et l'agrégation de la grille
 * Grille des engagements d'une entreprise.
 *
 * Toute la logique est calculée à la volée à partir du référentiel
 * `engagement_categories` (taxonomie) et des saisies `engagement_lignes`.
 */
class EngagementGridService
{
    /**
     * Construit l'arborescence complète (sections > rubriques > natures > produits)
     * avec, pour chaque produit, les lignes saisies pour l'entreprise.
     *
     * @return array<int, array<string, mixed>> Liste de noeuds racines.
     */
    public function buildTreeForEntreprise(int $entrepriseId, ?int $partenaireId = null, string $search = ''): array
    {
        /** @var Collection<int, EngagementCategorie> $categories */
        $categories = EngagementCategorie::query()
            ->orderBy('sort_order')
            ->get();

        $byParent = $categories->groupBy(fn (EngagementCategorie $c) => $c->parent_id ?? 0);

        $lignes = $this->loadLignesForEntreprise($entrepriseId, $partenaireId);
        $lignesByCategorie = $lignes->groupBy('engagement_categorie_id');

        $rootNodes = [];
        foreach ($byParent->get(0, collect()) as $root) {
            $node = $this->hydrateNode($root, $byParent, $lignesByCategorie, 0);
            if ($node !== null) {
                $rootNodes[] = $node;
            }
        }

        $needle = trim($search);
        if ($needle !== '') {
            $rootNodes = array_values(array_filter(array_map(
                fn (array $node) => $this->applySearchFilterToNode($node, mb_strtolower($needle)),
                $rootNodes
            )));
        }

        return $rootNodes;
    }

    /**
     * Aplatit l'arborescence (utile pour les composants de tableau et l'export).
     *
     * @return array<int, array<string, mixed>>
     */
    public function flattenTree(array $tree): array
    {
        $rows = [];
        $walker = function (array $node, int $depth) use (&$rows, &$walker) {
            $row = $node;
            unset($row['children'], $row['lignes']);
            $row['depth'] = $depth;
            $row['has_children'] = ! empty($node['children'] ?? []) || ! empty($node['lignes'] ?? []);
            $rows[] = $row;

            foreach ($node['children'] ?? [] as $child) {
                $walker($child, $depth + 1);
            }
        };
        foreach ($tree as $root) {
            $walker($root, 0);
        }

        return $rows;
    }

    /**
     * Statistiques agrégées au niveau entreprise (sommes des feuilles).
     *
     * @return array{
     *   nb_lignes:int,
     *   encours_initial:float,
     *   encours_actuel:float,
     *   encours_remboursement_n1:float,
     *   encours_retards:float,
     *   encours_impayes:float,
     *   sollicite_montant:float,
     *   total_montant:float,
     *   variation:float
     * }
     */
    public function buildStatsForEntreprise(int $entrepriseId, ?int $partenaireId = null, string $search = ''): array
    {
        $tree = $this->buildTreeForEntreprise($entrepriseId, $partenaireId, $search);

        $totals = [
            'nb_lignes' => 0,
            'encours_initial' => 0.0,
            'encours_actuel' => 0.0,
            'encours_remboursement_n1' => 0.0,
            'encours_retards' => 0.0,
            'encours_impayes' => 0.0,
            'sollicite_montant' => 0.0,
            'total_montant' => 0.0,
            'variation' => 0.0,
        ];

        $walker = function (array $node) use (&$walker, &$totals): void {
            if (($node['is_leaf'] ?? false) === true) {
                foreach ($node['lignes'] ?? [] as $ligne) {
                    $totals['nb_lignes']++;
                    $totals['encours_initial'] += (float) ($ligne['encours_initial'] ?? 0);
                    $totals['encours_actuel'] += (float) ($ligne['encours_actuel'] ?? 0);
                    $totals['encours_remboursement_n1'] += (float) ($ligne['encours_remboursement_n1'] ?? 0);
                    $totals['encours_retards'] += (float) ($ligne['encours_retards'] ?? 0);
                    $totals['encours_impayes'] += (float) ($ligne['encours_impayes'] ?? 0);
                    $totals['sollicite_montant'] += (float) ($ligne['sollicite_montant'] ?? 0);
                }
            }
            foreach ($node['children'] ?? [] as $child) {
                $walker($child);
            }
        };

        foreach ($tree as $root) {
            $walker($root);
        }

        $totals['total_montant'] = $totals['encours_actuel'] + $totals['sollicite_montant'];
        $totals['variation'] = $totals['sollicite_montant'] - $totals['encours_actuel'];

        return $totals;
    }

    /**
     * Liste des partenaires (banques / EMF / autres) pour les filtres et selects.
     *
     * @return array{banques: array, emfs: array, autres: array, all: array}
     */
    public function partenairesGrouped(): array
    {
        $partenaires = Banque::query()
            ->where(function ($q) {
                $q->where('actif', true)->orWhereNull('actif');
            })
            ->orderBy('name')
            ->get(['id', 'name', 'kind', 'siege']);

        $byKind = $partenaires->groupBy(fn (Banque $b) => $b->kind ?? Banque::KIND_BANQUE);

        return [
            'banques' => $byKind->get(Banque::KIND_BANQUE, collect())->values()->all(),
            'emfs' => $byKind->get(Banque::KIND_EMF, collect())->values()->all(),
            'autres' => $byKind->get(Banque::KIND_AUTRE, collect())->values()->all(),
            'all' => $partenaires->values()->all(),
        ];
    }

    /**
     * Construit les lignes d'export (PDF/Excel) en aplatissant la grille.
     *
     * @return array{headers: array<string,string>, rows: array<int, array<string,string>>}
     */
    public function buildExportTable(int $entrepriseId, ?int $partenaireId = null, string $search = ''): array
    {
        $tree = $this->buildTreeForEntreprise($entrepriseId, $partenaireId, $search);
        $rows = [];
        $walker = function (array $node, int $depth) use (&$walker, &$rows): void {
            $prefix = str_repeat('   ', max(0, $depth));
            if (($node['is_leaf'] ?? false) === true) {
                $lignes = $node['lignes'] ?? [];
                if (empty($lignes)) {
                    $rows[] = $this->emptyRowForNode($prefix.$node['libelle']);
                } else {
                    foreach ($lignes as $ligne) {
                        $rows[] = $this->ligneRow($prefix.$node['libelle'], $ligne);
                    }
                }
            } else {
                $rows[] = $this->aggregatedRowForBranch($prefix.$node['libelle'], $node);
                foreach ($node['children'] ?? [] as $child) {
                    $walker($child, $depth + 1);
                }
            }
        };
        foreach ($tree as $root) {
            $walker($root, 0);
        }

        return [
            'headers' => [
                'engagement' => 'Engagement',
                'partenaire' => 'Partenaire',
                'kind' => 'Type',
                'encours_initial' => 'Encours initial',
                'encours_actuel' => 'Encours actuel',
                'encours_remboursement_n1' => 'Remb. N-1',
                'encours_retards' => 'Retards',
                'encours_impayes' => 'Impayés',
                'encours_statut' => 'Statut',
                'encours_date_validite' => 'Date encours',
                'sollicite_montant' => 'Sollicité',
                'sollicite_date_validite' => 'Date sollicité',
                'total_montant' => 'Total',
                'commentaire' => 'Commentaire',
            ],
            'rows' => $rows,
        ];
    }

    // -----------------------------------------------------------------------
    // Internes
    // -----------------------------------------------------------------------

    /**
     * @param  Collection<int, EngagementLigne>  $lignesByCategorie
     */
    private function hydrateNode(
        EngagementCategorie $categorie,
        \Illuminate\Support\Collection $byParent,
        \Illuminate\Support\Collection $lignesByCategorie,
        int $depth,
    ): array {
        $children = [];
        foreach ($byParent->get($categorie->id, collect()) as $child) {
            $children[] = $this->hydrateNode($child, $byParent, $lignesByCategorie, $depth + 1);
        }

        $lignes = [];
        $aggregates = [
            'encours_initial' => 0.0,
            'encours_actuel' => 0.0,
            'encours_remboursement_n1' => 0.0,
            'encours_retards' => 0.0,
            'encours_impayes' => 0.0,
            'sollicite_montant' => 0.0,
        ];

        if ($categorie->is_leaf) {
            /** @var Collection<int, EngagementLigne> $rawLignes */
            $rawLignes = $lignesByCategorie->get($categorie->id, collect());
            foreach ($rawLignes as $ligne) {
                $row = $this->serializeLigne($ligne, $categorie->libelle);
                $lignes[] = $row;
                $aggregates['encours_initial'] += (float) $row['encours_initial'];
                $aggregates['encours_actuel'] += (float) $row['encours_actuel'];
                $aggregates['encours_remboursement_n1'] += (float) $row['encours_remboursement_n1'];
                $aggregates['encours_retards'] += (float) $row['encours_retards'];
                $aggregates['encours_impayes'] += (float) $row['encours_impayes'];
                $aggregates['sollicite_montant'] += (float) $row['sollicite_montant'];
            }
        } else {
            foreach ($children as $child) {
                $aggregates['encours_initial'] += (float) ($child['encours_initial'] ?? 0);
                $aggregates['encours_actuel'] += (float) ($child['encours_actuel'] ?? 0);
                $aggregates['encours_remboursement_n1'] += (float) ($child['encours_remboursement_n1'] ?? 0);
                $aggregates['encours_retards'] += (float) ($child['encours_retards'] ?? 0);
                $aggregates['encours_impayes'] += (float) ($child['encours_impayes'] ?? 0);
                $aggregates['sollicite_montant'] += (float) ($child['sollicite_montant'] ?? 0);
            }
        }

        return [
            'id' => $categorie->id,
            'code' => $categorie->code,
            'libelle' => $categorie->libelle,
            'type' => $categorie->type,
            'is_leaf' => $categorie->is_leaf,
            'depth' => $depth,
            'sort_order' => $categorie->sort_order,
            'description' => $categorie->description,
            'children' => $children,
            'lignes' => $lignes,
            'encours_initial' => $aggregates['encours_initial'],
            'encours_actuel' => $aggregates['encours_actuel'],
            'encours_remboursement_n1' => $aggregates['encours_remboursement_n1'],
            'encours_retards' => $aggregates['encours_retards'],
            'encours_impayes' => $aggregates['encours_impayes'],
            'sollicite_montant' => $aggregates['sollicite_montant'],
            'total_montant' => $aggregates['encours_actuel'] + $aggregates['sollicite_montant'],
            'variation' => $aggregates['sollicite_montant'] - $aggregates['encours_actuel'],
        ];
    }

    /**
     * @return Collection<int, EngagementLigne>
     */
    private function loadLignesForEntreprise(int $entrepriseId, ?int $partenaireId): Collection
    {
        $query = EngagementLigne::query()
            ->where('entreprise_id', $entrepriseId)
            ->with(['partenaire', 'updatedByUser:id,name', 'createdByUser:id,name'])
            ->orderBy('id');

        if ($partenaireId !== null && $partenaireId > 0) {
            $query->where('partenaire_id', $partenaireId);
        }

        return $query->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeLigne(EngagementLigne $ligne, ?string $engagementCategorieLibelle = null): array
    {
        $partenaire = $ligne->partenaire;

        return [
            'id' => $ligne->id,
            'engagement_categorie_id' => $ligne->engagement_categorie_id,
            'engagement_categorie_libelle' => $engagementCategorieLibelle,
            'partenaire_id' => $ligne->partenaire_id,
            'partenaire_nom' => $partenaire?->name,
            'partenaire_kind' => $partenaire?->kind ?? null,
            'partenaire_kind_label' => $partenaire?->kind_label ?? null,
            'encours_initial' => (float) $ligne->encours_initial,
            'encours_actuel' => (float) $ligne->encours_actuel,
            'encours_remboursement_n1' => (float) $ligne->encours_remboursement_n1,
            'encours_retards' => (float) $ligne->encours_retards,
            'encours_impayes' => (float) $ligne->encours_impayes,
            'encours_statut' => $ligne->encours_statut,
            'encours_statut_label' => $ligne->encours_statut
                ? (EngagementLigne::STATUTS[$ligne->encours_statut] ?? $ligne->encours_statut)
                : null,
            'encours_date_validite' => $ligne->encours_date_validite?->format('Y-m-d'),
            'sollicite_montant' => (float) $ligne->sollicite_montant,
            'sollicite_date_validite' => $ligne->sollicite_date_validite?->format('Y-m-d'),
            'commentaire' => $ligne->commentaire,
            'total_montant' => (float) $ligne->total_montant,
            'variation' => (float) $ligne->variation,
            'updated_by' => $ligne->updatedByUser?->name,
            'updated_at' => $ligne->updated_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Filtre récursivement un nœud sur le libellé / partenaire (recherche libre).
     *
     * @param  array<string,mixed>  $node
     * @return array<string,mixed>|null
     */
    private function applySearchFilterToNode(array $node, string $needle): ?array
    {
        $libelle = mb_strtolower((string) ($node['libelle'] ?? ''));
        $matchesSelf = $libelle !== '' && str_contains($libelle, $needle);

        if (($node['is_leaf'] ?? false) === true) {
            $lignes = array_values(array_filter($node['lignes'] ?? [], function (array $ligne) use ($needle, $matchesSelf) {
                if ($matchesSelf) {
                    return true;
                }
                $hay = mb_strtolower(
                    trim(
                        (string) ($ligne['partenaire_nom'] ?? '').' '.
                        (string) ($ligne['encours_statut_label'] ?? '').' '.
                        (string) ($ligne['commentaire'] ?? '')
                    )
                );

                return $hay !== '' && str_contains($hay, $needle);
            }));

            if (! $matchesSelf && empty($lignes)) {
                return null;
            }
            $node['lignes'] = $lignes;

            // Recalcule les agrégats basés sur les lignes filtrées.
            $node['encours_initial'] = array_sum(array_map(fn ($l) => (float) ($l['encours_initial'] ?? 0), $lignes));
            $node['encours_actuel'] = array_sum(array_map(fn ($l) => (float) ($l['encours_actuel'] ?? 0), $lignes));
            $node['encours_remboursement_n1'] = array_sum(array_map(fn ($l) => (float) ($l['encours_remboursement_n1'] ?? 0), $lignes));
            $node['encours_retards'] = array_sum(array_map(fn ($l) => (float) ($l['encours_retards'] ?? 0), $lignes));
            $node['encours_impayes'] = array_sum(array_map(fn ($l) => (float) ($l['encours_impayes'] ?? 0), $lignes));
            $node['sollicite_montant'] = array_sum(array_map(fn ($l) => (float) ($l['sollicite_montant'] ?? 0), $lignes));
            $node['total_montant'] = $node['encours_actuel'] + $node['sollicite_montant'];
            $node['variation'] = $node['sollicite_montant'] - $node['encours_actuel'];

            return $node;
        }

        $children = array_values(array_filter(array_map(
            fn ($child) => is_array($child) ? $this->applySearchFilterToNode($child, $needle) : null,
            $node['children'] ?? []
        )));

        if (! $matchesSelf && empty($children)) {
            return null;
        }

        $node['children'] = $children;
        $node['encours_initial'] = array_sum(array_map(fn ($c) => (float) ($c['encours_initial'] ?? 0), $children));
        $node['encours_actuel'] = array_sum(array_map(fn ($c) => (float) ($c['encours_actuel'] ?? 0), $children));
        $node['encours_remboursement_n1'] = array_sum(array_map(fn ($c) => (float) ($c['encours_remboursement_n1'] ?? 0), $children));
        $node['encours_retards'] = array_sum(array_map(fn ($c) => (float) ($c['encours_retards'] ?? 0), $children));
        $node['encours_impayes'] = array_sum(array_map(fn ($c) => (float) ($c['encours_impayes'] ?? 0), $children));
        $node['sollicite_montant'] = array_sum(array_map(fn ($c) => (float) ($c['sollicite_montant'] ?? 0), $children));
        $node['total_montant'] = $node['encours_actuel'] + $node['sollicite_montant'];
        $node['variation'] = $node['sollicite_montant'] - $node['encours_actuel'];

        return $node;
    }

    /**
     * @param  array<string,mixed>  $ligne
     * @return array<string,string>
     */
    private function ligneRow(string $libelleAvecIndent, array $ligne): array
    {
        return [
            'engagement' => $libelleAvecIndent,
            'partenaire' => (string) Arr::get($ligne, 'partenaire_nom', '—'),
            'kind' => (string) Arr::get($ligne, 'partenaire_kind_label', '—'),
            'encours_initial' => $this->fmt(Arr::get($ligne, 'encours_initial', 0)),
            'encours_actuel' => $this->fmt(Arr::get($ligne, 'encours_actuel', 0)),
            'encours_remboursement_n1' => $this->fmt(Arr::get($ligne, 'encours_remboursement_n1', 0)),
            'encours_retards' => $this->fmt(Arr::get($ligne, 'encours_retards', 0)),
            'encours_impayes' => $this->fmt(Arr::get($ligne, 'encours_impayes', 0)),
            'encours_statut' => (string) Arr::get($ligne, 'encours_statut_label', '—'),
            'encours_date_validite' => $this->fmtDate(Arr::get($ligne, 'encours_date_validite')),
            'sollicite_montant' => $this->fmt(Arr::get($ligne, 'sollicite_montant', 0)),
            'sollicite_date_validite' => $this->fmtDate(Arr::get($ligne, 'sollicite_date_validite')),
            'total_montant' => $this->fmt(Arr::get($ligne, 'total_montant', 0)),
            'commentaire' => (string) Arr::get($ligne, 'commentaire', ''),
        ];
    }

    /**
     * @param  array<string,mixed>  $node
     * @return array<string,string>
     */
    private function aggregatedRowForBranch(string $libelleAvecIndent, array $node): array
    {
        return [
            'engagement' => $libelleAvecIndent,
            'partenaire' => '—',
            'kind' => '—',
            'encours_initial' => $this->fmt(Arr::get($node, 'encours_initial', 0)),
            'encours_actuel' => $this->fmt(Arr::get($node, 'encours_actuel', 0)),
            'encours_remboursement_n1' => $this->fmt(Arr::get($node, 'encours_remboursement_n1', 0)),
            'encours_retards' => $this->fmt(Arr::get($node, 'encours_retards', 0)),
            'encours_impayes' => $this->fmt(Arr::get($node, 'encours_impayes', 0)),
            'encours_statut' => '—',
            'encours_date_validite' => '—',
            'sollicite_montant' => $this->fmt(Arr::get($node, 'sollicite_montant', 0)),
            'sollicite_date_validite' => '—',
            'total_montant' => $this->fmt(Arr::get($node, 'total_montant', 0)),
            'commentaire' => '',
        ];
    }

    /**
     * @return array<string,string>
     */
    private function emptyRowForNode(string $libelleAvecIndent): array
    {
        return [
            'engagement' => $libelleAvecIndent,
            'partenaire' => '—',
            'kind' => '—',
            'encours_initial' => '0',
            'encours_actuel' => '0',
            'encours_remboursement_n1' => '0',
            'encours_retards' => '0',
            'encours_impayes' => '0',
            'encours_statut' => '—',
            'encours_date_validite' => '—',
            'sollicite_montant' => '0',
            'sollicite_date_validite' => '—',
            'total_montant' => '0',
            'commentaire' => '',
        ];
    }

    private function fmt(mixed $v): string
    {
        $n = is_numeric($v) ? (float) $v : 0.0;

        return number_format($n, 0, ',', ' ');
    }

    private function fmtDate(mixed $v): string
    {
        if (! $v) {
            return '—';
        }
        try {
            return \Carbon\Carbon::parse((string) $v)->format('d/m/Y');
        } catch (\Throwable) {
            return (string) $v;
        }
    }
}
