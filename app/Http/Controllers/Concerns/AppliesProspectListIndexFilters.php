<?php

namespace App\Http\Controllers\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait AppliesProspectListIndexFilters
{
    protected function normalizeProspectDateInput(mixed $value): ?string
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
     * @return array{
     *     region_id: mixed,
     *     taille: mixed,
     *     forme_id: mixed,
     *     caractere: mixed,
     *     submission: ?string,
     *     agence_id: ?int,
     *     gestionnaire_id: ?int,
     *     created_from: ?string,
     *     created_to: ?string
     * }
     */
    protected function parseProspectIndexFilters(Request $request): array
    {
        $sub = $request->input('submission');
        $sub = in_array((string) $sub, ['draft', 'submitted'], true) ? (string) $sub : null;

        return [
            'region_id' => $request->input('region_id'),
            'taille' => $request->input('taille'),
            'forme_id' => $request->input('forme_id'),
            'caractere' => $request->input('caractere'),
            'submission' => $sub,
            'agence_id' => $request->filled('agence_id') ? (int) $request->input('agence_id') : null,
            'gestionnaire_id' => $request->filled('gestionnaire_id') ? (int) $request->input('gestionnaire_id') : null,
            'created_from' => $this->normalizeProspectDateInput($request->input('created_from')),
            'created_to' => $this->normalizeProspectDateInput($request->input('created_to')),
        ];
    }

    /**
     * @param  Builder<\App\Models\Entreprise>  $query
     * @param  array{apply_submission?: bool}  $options  apply_submission: false pour les listes déjà limitées aux soumis (ex. chef d'agence).
     * @return Builder<\App\Models\Entreprise>
     */
    protected function applyProspectIndexFilters(Builder $query, array $filters, array $options = []): Builder
    {
        $applySubmission = $options['apply_submission'] ?? true;

        if (! empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (! empty($filters['taille'])) {
            $query->where('taille', $filters['taille']);
        }
        if (! empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }
        if (! empty($filters['caractere'])) {
            $query->where('caractere', $filters['caractere']);
        }

        if ($applySubmission && ! empty($filters['submission'])) {
            if ($filters['submission'] === 'draft') {
                $query->whereNull('prospect_submitted_at');
            } elseif ($filters['submission'] === 'submitted') {
                $query->whereNotNull('prospect_submitted_at');
            }
        }

        if (! empty($filters['agence_id'])) {
            $query->where('agence_id', (int) $filters['agence_id']);
        }
        if (! empty($filters['gestionnaire_id'])) {
            $query->where('gestionnaire_id', (int) $filters['gestionnaire_id']);
        }

        if (! empty($filters['created_from'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['created_from'])->startOfDay());
        }
        if (! empty($filters['created_to'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['created_to'])->endOfDay());
        }

        return $query;
    }
}
