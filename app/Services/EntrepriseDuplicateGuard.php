<?php

namespace App\Services;

use App\Models\Entreprise;
use Illuminate\Validation\ValidationException;

/**
 * Empêche l'enregistrement d'un client ou d'un prospect en double sur la base du NIU ou du RCCM.
 *
 * La comparaison ignore la casse et les espaces (« rc / dla 2020 » = « RC/DLA2020 »), pour couvrir
 * les saisies antérieures non normalisées. Appelé automatiquement par le modèle Entreprise
 * (événement « saving ») : tous les chemins de création / modification sont donc couverts.
 */
class EntrepriseDuplicateGuard
{
    public const IDENTIFIERS = ['niu' => 'NIU', 'rccm' => 'RCCM'];

    public static function normalize(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        return $value === '' ? null : strtoupper($value);
    }

    /**
     * Première fiche (client ou prospect) portant le même NIU ou RCCM, hors fiche $ignoreId.
     *
     * @return array{field: string, entreprise: Entreprise}|null
     */
    public function find(?string $niu, ?string $rccm, ?int $ignoreId = null): ?array
    {
        foreach (['niu' => $niu, 'rccm' => $rccm] as $field => $value) {
            $key = $this->comparisonKey($value);
            if ($key === null) {
                continue;
            }

            $entreprise = Entreprise::query()
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->whereRaw("REPLACE(UPPER(TRIM({$field})), ' ', '') = ?", [$key])
                ->orderBy('id')
                ->first();

            if ($entreprise) {
                return ['field' => $field, 'entreprise' => $entreprise];
            }
        }

        return null;
    }

    /**
     * Fiche existante à réutiliser lors de l'ajout d'un tiers personne morale : NIU / RCCM d'abord,
     * puis téléphone ou e-mail (uniquement s'ils sont renseignés).
     *
     * @param  array<string, mixed>  $data
     */
    public function findForLinking(array $data, ?int $ignoreId = null): ?Entreprise
    {
        $byIdentifier = $this->find($data['niu'] ?? null, $data['rccm'] ?? null, $ignoreId);
        if ($byIdentifier) {
            return $byIdentifier['entreprise'];
        }

        $contacts = array_filter([
            'phone' => trim((string) ($data['phone'] ?? '')),
            'email' => trim((string) ($data['email'] ?? '')),
        ]);
        if ($contacts === []) {
            return null;
        }

        return Entreprise::query()
            ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
            ->where(function ($q) use ($contacts) {
                foreach ($contacts as $field => $value) {
                    $q->orWhere($field, $value);
                }
            })
            ->orderBy('id')
            ->first();
    }

    /**
     * @throws ValidationException si le NIU ou le RCCM est déjà utilisé par une autre fiche.
     */
    public function assertUnique(?string $niu, ?string $rccm, ?int $ignoreId = null): void
    {
        $duplicate = $this->find($niu, $rccm, $ignoreId);
        if ($duplicate === null) {
            return;
        }

        throw ValidationException::withMessages([
            $duplicate['field'] => $this->message($duplicate['field'], $duplicate['entreprise']),
        ]);
    }

    public function message(string $field, Entreprise $existing): string
    {
        $existing->loadMissing(['gestionnaire', 'agence']);

        $details = array_filter([
            $existing->prospect ? 'prospect' : 'client',
            $existing->gestionnaire?->name ? 'gestionnaire : '.$existing->gestionnaire->name : null,
            $existing->agence?->name ? 'agence : '.$existing->agence->name : null,
        ]);

        return sprintf(
            'Ce %s est déjà enregistré pour « %s » (%s). Utilisez la fiche existante au lieu d’en créer une nouvelle.',
            self::IDENTIFIERS[$field],
            $existing->name ?: 'fiche sans dénomination',
            implode(', ', $details)
        );
    }

    private function comparisonKey(?string $value): ?string
    {
        $value = self::normalize($value);

        return $value === null ? null : str_replace(' ', '', $value);
    }
}
