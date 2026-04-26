<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AppliesEntrepriseListIndexFilters
{
    /**
     * @return array{promu_client_from: ?string, promu_client_to: ?string, agence_id: ?int, gestionnaire_id: ?int}
     */
    protected function parsePromuClientAndAgenceGestionnaireFilters(Request $request, bool $includeAgenceGestionnaire): array
    {
        $out = [
            'promu_client_from' => $this->normalizeDateFilterInput($request->input('promu_client_from')),
            'promu_client_to' => $this->normalizeDateFilterInput($request->input('promu_client_to')),
            'agence_id' => null,
            'gestionnaire_id' => null,
        ];
        if ($includeAgenceGestionnaire) {
            $out['agence_id'] = $request->filled('agence_id') ? (int) $request->input('agence_id') : null;
            $out['gestionnaire_id'] = $request->filled('gestionnaire_id') ? (int) $request->input('gestionnaire_id') : null;
        }

        return $out;
    }

    protected function normalizeDateFilterInput(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }
        $s = trim((string) $value);
        if ($s === '') {
            return null;
        }

        try {
            return Carbon::parse($s)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Filtre sur la date de passage prospect → client (`promu_client_at`).
     *
     * @param  Builder<\App\Models\Entreprise>  $query
     */
    protected function applyPromuClientValidatedPeriodFilter(Builder $query, ?string $from, ?string $to): void
    {
        if ($from === null && $to === null) {
            return;
        }
        $query->whereNotNull('promu_client_at');
        if ($from !== null) {
            $query->where('promu_client_at', '>=', Carbon::parse($from)->startOfDay());
        }
        if ($to !== null) {
            $query->where('promu_client_at', '<=', Carbon::parse($to)->endOfDay());
        }
    }

    /**
     * @param  Builder<\App\Models\Entreprise>  $query
     */
    protected function applyAgenceAndGestionnaireFilters(Builder $query, ?int $agenceId, ?int $gestionnaireId): void
    {
        if ($agenceId) {
            $query->where('agence_id', $agenceId);
        }
        if ($gestionnaireId) {
            $query->where('gestionnaire_id', $gestionnaireId);
        }
    }

    /**
     * @param  callable(?int): (?int)  $sanitizeAgenceId
     * @param  Builder<\App\Models\Entreprise>  $query
     */
    protected function applyPromuAgenceGestionnaireFiltersToQuery(
        Builder $query,
        array $filters,
        bool $includeAgenceGestionnaire,
        ?callable $sanitizeAgenceId = null,
    ): void {
        $this->applyPromuClientValidatedPeriodFilter(
            $query,
            $filters['promu_client_from'] ?? null,
            $filters['promu_client_to'] ?? null,
        );
        if (! $includeAgenceGestionnaire) {
            return;
        }
        $aid = $filters['agence_id'] ?? null;
        if ($sanitizeAgenceId !== null) {
            $aid = $sanitizeAgenceId($aid);
        }
        $gid = isset($filters['gestionnaire_id']) && (int) $filters['gestionnaire_id'] > 0
            ? (int) $filters['gestionnaire_id']
            : null;
        $this->applyAgenceAndGestionnaireFilters($query, $aid ?: null, $gid);
    }
}
