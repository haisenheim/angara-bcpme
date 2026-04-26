<?php

namespace App\Services;

use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use Illuminate\Support\Collection;

class EngagementReportService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildRowsForEntreprise(int $entrepriseId): array
    {
        $engagements = Engagement::query()->where('parent_id', 0)->get();
        $data = [];
        foreach ($engagements as $eng) {
            $data[] = $this->parseEngagement($eng, $entrepriseId);
        }

        return $data;
    }

    /**
     * Construit les lignes pour l'affichage (AngaraTable):
     * - optionnellement filtrées par banque (recalcule les totaux)
     * - optionnellement filtrées par recherche (sur libellé engagement)
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildUiRowsForEntreprise(int $entrepriseId, ?int $banqueId = null, string $search = ''): array
    {
        $tree = $this->buildRowsForEntreprise($entrepriseId);

        if ($banqueId) {
            $tree = array_values(array_filter(array_map(function (array $node) use ($banqueId) {
                return $this->applyBanqueFilterToNode($node, $banqueId);
            }, $tree)));
        }

        $rows = [];
        foreach ($tree as $node) {
            $this->flattenForUi($rows, $node);
        }

        $search = trim($search);
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $rows = array_values(array_filter($rows, function (array $row) use ($needle) {
                $hay = mb_strtolower(trim((string) ($row['name'] ?? '')));
                return $hay !== '' && str_contains($hay, $needle);
            }));
        }

        return $rows;
    }

    /**
     * @return array{headers: array<string,string>, rows: array<int, array<string, mixed>>}
     */
    public function buildExportTableForEntreprise(int $entrepriseId, ?int $banqueId = null, string $search = ''): array
    {
        $tree = $this->buildRowsForEntreprise($entrepriseId);

        $headers = [
            'engagement' => 'Engagement',
            'banque' => 'Banque',
            'encours_montant' => 'Encours (montant)',
            'encours_impaye' => 'Encours (impayés)',
            'encours_dt_validite' => 'Date validité encours',
            'sollicite_montant' => 'Sollicité (montant)',
            'sollicite_dt_validite' => 'Date validité sollicité',
            'variation' => 'Variation',
        ];

        $rows = [];
        foreach ($tree as $node) {
            $this->flattenForExport($rows, $node, $banqueId);
        }

        $search = trim($search);
        if ($search !== '') {
            $needle = mb_strtolower($search);
            $rows = array_values(array_filter($rows, function (array $row) use ($needle) {
                $hay = mb_strtolower(
                    trim(((string) ($row['engagement'] ?? '')).' '.((string) ($row['banque'] ?? '')))
                );

                return $hay !== '' && str_contains($hay, $needle);
            }));
        }

        return ['headers' => $headers, 'rows' => $rows];
    }

    /**
     * Stats agrégées pour l'affichage (évite double-compte: uniquement les feuilles).
     *
     * @return array{encours_montant:int, encours_impaye:int, sollicite_montant:int, variation:int, total_montant:int, leaf_count:int}
     */
    public function buildUiStatsForEntreprise(int $entrepriseId, ?int $banqueId = null, string $search = ''): array
    {
        $rows = $this->buildUiRowsForEntreprise($entrepriseId, $banqueId, $search);

        $leafRows = array_values(array_filter($rows, function (array $row) {
            return (bool) ($row['is_leaf'] ?? false);
        }));

        $encours = 0;
        $impayes = 0;
        $sollicite = 0;
        $variation = 0;

        foreach ($leafRows as $row) {
            $encours += (int) ($row['encours_montant'] ?? 0);
            $impayes += (int) ($row['encours_impaye'] ?? 0);
            $sollicite += (int) ($row['sollicite_montant'] ?? 0);
            $variation += (int) ($row['variation'] ?? 0);
        }

        return [
            'encours_montant' => $encours,
            'encours_impaye' => $impayes,
            'sollicite_montant' => $sollicite,
            'variation' => $variation,
            'total_montant' => $encours + $sollicite,
            'leaf_count' => count($leafRows),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function parseEngagement(Engagement $eng, int $entrepriseId): array
    {
        $data = [
            'id' => $eng->id,
            'name' => $eng->name,
            'montant' => $eng->montant ?? 0,
            'encours_montant' => $eng->encours_montant ?? 0,
            'encours_impaye' => $eng->encours_impaye ?? 0,
            'sollicite_montant' => $eng->sollicite_montant ?? 0,
            'variation' => $eng->variation,
            'parent_id' => $eng->parent_id,
            'is_title' => $eng->is_title,
            'is_leaf' => $eng->is_leaf,
            'niveau' => $eng->niveau,
        ];
        if ($data['is_leaf']) {
            $elts = EngagementEntreprise::with('banque')
                ->where('engagement_id', $eng->id)
                ->where('entreprise_id', $entrepriseId)
                ->get();
            $data['encours_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_montant ?? 0);
            }, 0);
            $data['sollicite_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->sollicite_montant ?? 0);
            }, 0);
            $data['encours_impaye'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_impaye ?? 0);
            }, 0);
            $data['elts'] = $elts->map(function ($elt) {
                return [
                    'banque_id' => $elt->banque_id,
                    'banque_name' => $elt->banque?->name ?? '—',
                    'encours_montant' => $elt->encours_montant ?? 0,
                    'encours_impaye' => $elt->encours_impaye ?? 0,
                    'encours_dt_validite' => $elt->encours_dt_validite ?? '—',
                    'sollicite_montant' => $elt->sollicite_montant ?? 0,
                    'sollicite_dt_validite' => $elt->sollicite_dt_validite ?? '—',
                ];
            })->values()->toArray();
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];
        } else {
            $data['children'] = $eng->children->map(function ($child) use ($entrepriseId) {
                return $this->parseEngagement($child, $entrepriseId);
            })->values()->all();
            foreach ($data['children'] as $child) {
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }

        return $data;
    }

    /**
     * Applique un filtre banque sur un noeud (recalcule montants et élague si vide).
     * Retourne null si le noeud devient vide/inutile pour cette banque.
     *
     * @param  array<string,mixed>  $node
     * @return array<string,mixed>|null
     */
    private function applyBanqueFilterToNode(array $node, int $banqueId): ?array
    {
        if (($node['is_leaf'] ?? false) && isset($node['elts']) && is_array($node['elts'])) {
            $elts = collect($node['elts'])->filter(function (array $elt) use ($banqueId) {
                return ((int) ($elt['banque_id'] ?? 0)) === $banqueId;
            })->values();

            $node['elts'] = $elts->all();
            $node['encours_montant'] = (int) $elts->sum(fn (array $elt) => (int) ($elt['encours_montant'] ?? 0));
            $node['sollicite_montant'] = (int) $elts->sum(fn (array $elt) => (int) ($elt['sollicite_montant'] ?? 0));
            $node['encours_impaye'] = (int) $elts->sum(fn (array $elt) => (int) ($elt['encours_impaye'] ?? 0));
            $node['variation'] = (int) ($node['sollicite_montant'] ?? 0) - (int) ($node['encours_montant'] ?? 0);

            $hasAny = ((int) ($node['encours_montant'] ?? 0)) !== 0
                || ((int) ($node['sollicite_montant'] ?? 0)) !== 0
                || ((int) ($node['encours_impaye'] ?? 0)) !== 0;

            return $hasAny ? $node : null;
        }

        if (isset($node['children']) && is_array($node['children'])) {
            $children = array_values(array_filter(array_map(function ($child) use ($banqueId) {
                return is_array($child) ? $this->applyBanqueFilterToNode($child, $banqueId) : null;
            }, $node['children'])));

            $node['children'] = $children;
            $node['encours_montant'] = 0;
            $node['sollicite_montant'] = 0;
            $node['encours_impaye'] = 0;
            $node['variation'] = 0;
            foreach ($children as $child) {
                $node['encours_montant'] += (int) ($child['encours_montant'] ?? 0);
                $node['sollicite_montant'] += (int) ($child['sollicite_montant'] ?? 0);
                $node['encours_impaye'] += (int) ($child['encours_impaye'] ?? 0);
                $node['variation'] += (int) ($child['variation'] ?? 0);
            }

            $hasAny = count($children) > 0;
            return $hasAny ? $node : null;
        }

        return null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, mixed>  $node
     */
    private function flattenForUi(array &$rows, array $node): void
    {
        $rows[] = [
            'id' => $node['id'] ?? null,
            'name' => (string) ($node['name'] ?? ''),
            'niveau' => (int) ($node['niveau'] ?? 0),
            'is_leaf' => (bool) ($node['is_leaf'] ?? false),
            'encours_montant' => (int) ($node['encours_montant'] ?? 0),
            'encours_impaye' => (int) ($node['encours_impaye'] ?? 0),
            'sollicite_montant' => (int) ($node['sollicite_montant'] ?? 0),
            'variation' => (int) ($node['variation'] ?? 0),
            'total_montant' => (int) (($node['encours_montant'] ?? 0) + ($node['sollicite_montant'] ?? 0)),
            'payload' => $node,
        ];

        if (isset($node['children']) && is_array($node['children'])) {
            foreach ($node['children'] as $child) {
                if (is_array($child)) {
                    $this->flattenForUi($rows, $child);
                }
            }
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, mixed>  $node
     */
    private function flattenForExport(array &$rows, array $node, ?int $banqueId = null, int $depth = 0): void
    {
        $prefix = str_repeat('  ', max(0, $depth));
        $label = $prefix.((string) ($node['name'] ?? ''));

        // Leaf: export a line per banque (or aggregated “Toutes banques”)
        if (($node['is_leaf'] ?? false) && isset($node['elts']) && is_array($node['elts'])) {
            $elts = collect($node['elts']);

            if ($banqueId) {
                $elts = $elts->filter(function (array $elt) use ($banqueId) {
                    return ((int) ($elt['banque_id'] ?? 0)) === $banqueId;
                });
            }

            if ($elts->isEmpty()) {
                $rows[] = [
                    'engagement' => $label,
                    'banque' => $banqueId ? '—' : 'Toutes banques',
                    'encours_montant' => $this->fmtNumber($node['encours_montant'] ?? 0),
                    'encours_impaye' => $this->fmtNumber($node['encours_impaye'] ?? 0),
                    'encours_dt_validite' => '—',
                    'sollicite_montant' => $this->fmtNumber($node['sollicite_montant'] ?? 0),
                    'sollicite_dt_validite' => '—',
                    'variation' => $this->fmtNumber($node['variation'] ?? 0),
                ];

                return;
            }

            /** @var Collection<int, array<string,mixed>> $elts */
            foreach ($elts as $elt) {
                $rows[] = [
                    'engagement' => $label,
                    'banque' => (string) ($elt['banque_name'] ?? '—'),
                    'encours_montant' => $this->fmtNumber($elt['encours_montant'] ?? 0),
                    'encours_impaye' => $this->fmtNumber($elt['encours_impaye'] ?? 0),
                    'encours_dt_validite' => (string) ($elt['encours_dt_validite'] ?? '—'),
                    'sollicite_montant' => $this->fmtNumber($elt['sollicite_montant'] ?? 0),
                    'sollicite_dt_validite' => (string) ($elt['sollicite_dt_validite'] ?? '—'),
                    'variation' => $this->fmtNumber(($elt['sollicite_montant'] ?? 0) - ($elt['encours_montant'] ?? 0)),
                ];
            }

            return;
        }

        // Non-leaf: add an aggregate line, then recurse children.
        $rows[] = [
            'engagement' => $label,
            'banque' => '—',
            'encours_montant' => $this->fmtNumber($node['encours_montant'] ?? 0),
            'encours_impaye' => $this->fmtNumber($node['encours_impaye'] ?? 0),
            'encours_dt_validite' => '—',
            'sollicite_montant' => $this->fmtNumber($node['sollicite_montant'] ?? 0),
            'sollicite_dt_validite' => '—',
            'variation' => $this->fmtNumber($node['variation'] ?? 0),
        ];

        if (isset($node['children']) && is_array($node['children'])) {
            foreach ($node['children'] as $child) {
                if (is_array($child)) {
                    $this->flattenForExport($rows, $child, $banqueId, $depth + 1);
                }
            }
        }
    }

    private function fmtNumber(mixed $value): string
    {
        $n = is_numeric($value) ? (float) $value : 0.0;

        return number_format($n, 0, ',', ' ');
    }
}
