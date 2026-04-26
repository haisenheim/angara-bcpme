<?php

namespace App\Services;

use App\Models\Entreprise;
use Illuminate\Support\Collection;

class ProspectEntrepriseTableExportService
{
    private static function localisation(Entreprise $e): string
    {
        $loc = trim(($e->arrondissement?->name ?? '').(($e->relationLoaded('region') && $e->region) ? ' / '.$e->region->name : ''));
        if ($loc === '' && $e->region) {
            $loc = $e->region->name ?? '';
        }

        return $loc;
    }

    private static function statut(Entreprise $e): string
    {
        return $e->prospect_submitted_at ? 'Soumis' : 'Brouillon';
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsCa(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'rccm' => $e->rccm ?? '',
                'niu' => $e->niu ?? '',
                'dirigeant' => $e->manager ?? '',
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? $e->user?->name ?? '',
                'localisation' => self::localisation($e),
                'taille' => $e->taille ?? '',
                'capital' => $e->capital !== null ? (string) $e->capital : '',
                'caractere' => $e->caractere ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsGestionnaire(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'rccm' => $e->rccm ?? '',
                'niu' => $e->niu ?? '',
                'dirigeant' => $e->manager ?? '',
                'localisation' => self::localisation($e),
                'taille' => $e->taille ?? '',
                'capital' => $e->capital !== null ? (string) $e->capital : '',
                'caractere' => $e->caractere ?? '',
                'statut' => self::statut($e),
                'soumis_le' => optional($e->prospect_submitted_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsAnalyste(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'rccm' => $e->rccm ?? '',
                'niu' => $e->niu ?? '',
                'dirigeant' => $e->manager ?? '',
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? $e->user?->name ?? '',
                'localisation' => self::localisation($e),
                'taille' => $e->taille ?? '',
                'capital' => $e->capital !== null ? (string) $e->capital : '',
                'caractere' => $e->caractere ?? '',
                'statut' => self::statut($e),
                'soumis_le' => optional($e->prospect_submitted_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsAdminRegional(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'rccm' => $e->rccm ?? '',
                'niu' => $e->niu ?? '',
                'dirigeant' => $e->manager ?? '',
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? $e->user?->name ?? '',
                'region' => $e->region?->name ?? '',
                'departement' => $e->departement?->name ?? '',
                'localisation' => self::localisation($e),
                'taille' => $e->taille ?? '',
                'capital' => $e->capital !== null ? (string) $e->capital : '',
                'caractere' => $e->caractere ?? '',
                'statut' => self::statut($e),
                'soumis_le' => optional($e->prospect_submitted_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function headersCa(): array
    {
        return [
            'denomination' => 'Dénomination',
            'rccm' => 'RCCM',
            'niu' => 'NIU',
            'dirigeant' => 'Dirigeant',
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'localisation' => 'Localisation',
            'taille' => 'Taille',
            'capital' => 'Capital',
            'caractere' => 'Caractère',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headersGestionnaire(): array
    {
        return [
            'denomination' => 'Dénomination',
            'rccm' => 'RCCM',
            'niu' => 'NIU',
            'dirigeant' => 'Dirigeant',
            'localisation' => 'Localisation',
            'taille' => 'Taille',
            'capital' => 'Capital',
            'caractere' => 'Caractère',
            'statut' => 'Statut',
            'soumis_le' => 'Soumis le',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headersAnalyste(): array
    {
        return [
            'denomination' => 'Dénomination',
            'rccm' => 'RCCM',
            'niu' => 'NIU',
            'dirigeant' => 'Dirigeant',
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'localisation' => 'Localisation',
            'taille' => 'Taille',
            'capital' => 'Capital',
            'caractere' => 'Caractère',
            'statut' => 'Statut',
            'soumis_le' => 'Soumis le',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headersAdminRegional(): array
    {
        return [
            'denomination' => 'Dénomination',
            'rccm' => 'RCCM',
            'niu' => 'NIU',
            'dirigeant' => 'Dirigeant',
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'region' => 'Région',
            'departement' => 'Département',
            'localisation' => 'Localisation',
            'taille' => 'Taille',
            'capital' => 'Capital',
            'caractere' => 'Caractère',
            'statut' => 'Statut',
            'soumis_le' => 'Soumis le',
        ];
    }

    /**
     * @param  \Illuminate\Support\Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsReviewOpen(iterable $entreprises, string $role): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $avis = $role === 'juridique'
                ? ($e->juridique_avis_at ? 'Rendu' : 'En attente')
                : ($e->conformite_avis_at ? 'Rendu' : 'En attente');
            $out[] = [
                'denomination' => $e->name,
                'soumis_le' => optional($e->prospect_submitted_at)->format('d/m/Y H:i') ?? '',
                'agence' => $e->agence?->name ?? '',
                'avis' => $avis,
            ];
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function headersReviewOpen(string $role): array
    {
        $avisLabel = $role === 'juridique' ? 'Avis juridique' : 'Avis conformité';

        return [
            'denomination' => 'Dénomination',
            'soumis_le' => 'Soumis le',
            'agence' => 'Agence',
            'avis' => $avisLabel,
        ];
    }
}
