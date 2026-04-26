<?php

namespace App\Services;

use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use Illuminate\Support\Collection;

class ClientEntrepriseTableExportService
{
    public static function structurationLabel(Entreprise $e): string
    {
        if (! $e->promu_client_at) {
            return '—';
        }

        return DossierEntreeRelation::clientStructurationPresentation($e->dossierEntreeRelation)['label'] ?? '—';
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsPortfolio(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'forme' => $e->forme?->name ?? '',
                'statut' => $e->prospect ? 'Prospect' : 'Client',
                'structuration' => self::structurationLabel($e),
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? $e->user?->name ?? '',
                'nb_dossiers' => (int) ($e->dossiers_count ?? 0),
                'promu_client' => optional($e->promu_client_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsCaGestionnaireAnalyste(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $loc = trim(($e->arrondissement?->name ?? '').(($e->relationLoaded('region') && $e->region) ? ' / '.$e->region->name : ''));
            if ($loc === '' && $e->region) {
                $loc = $e->region->name ?? '';
            }
            $out[] = [
                'denomination' => $e->name,
                'rccm' => $e->rccm ?? '',
                'niu' => $e->niu ?? '',
                'dirigeant' => $e->manager ?? '',
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? '',
                'localisation' => $loc,
                'taille' => $e->taille ?? '',
                'structuration' => self::structurationLabel($e),
                'promu_client' => optional($e->promu_client_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
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
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? '',
                'structuration' => self::structurationLabel($e),
                'promu_client' => optional($e->promu_client_at)->format('d/m/Y H:i') ?? '',
                'region' => $e->region?->name ?? '',
                'departement' => $e->departement?->name ?? '',
            ];
        }

        return $out;
    }

    public static function rowsChefFiliereClients(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? '',
                'structuration' => self::structurationLabel($e),
                'promu_client' => optional($e->promu_client_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  Collection<int, Entreprise>|iterable<Entreprise>  $entreprises
     * @return array<int, array<string, mixed>>
     */
    public static function rowsJuridiqueIndex(iterable $entreprises): array
    {
        $out = [];
        foreach ($entreprises as $e) {
            $out[] = [
                'denomination' => $e->name,
                'niu' => $e->niu ?? '',
                'rccm' => $e->rccm ?? '',
                'agence' => $e->agence?->name ?? '',
                'gestionnaire' => $e->gestionnaire?->name ?? '',
                'structuration' => self::structurationLabel($e),
                'promu_client' => optional($e->promu_client_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, string>  $headers  slug colonne => libellé Excel/PDF
     */
    public static function download(
        array $rows,
        array $headers,
        string $format,
        string $filenameSlug,
        string $documentTitle,
        string $subtitle = '',
    ): mixed {
        return TableDocumentExportService::downloadFormatted(
            $rows,
            $headers,
            $format,
            $filenameSlug,
            $documentTitle,
            $subtitle,
        );
    }

    /**
     * @return array<string, string>
     */
    public static function headersPortfolio(): array
    {
        return [
            'denomination' => 'Dénomination',
            'forme' => 'Forme juridique',
            'statut' => 'Statut',
            'structuration' => 'Structuration client',
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'nb_dossiers' => 'Nb dossiers',
            'promu_client' => 'Promu client',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headersCaGestionnaireAnalyste(): array
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
            'structuration' => 'Structuration client',
            'promu_client' => 'Promu client',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function headersChefFiliere(): array
    {
        return [
            'denomination' => 'Client',
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'structuration' => 'Structuration client',
            'promu_client' => 'Promu client',
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
            'agence' => 'Agence',
            'gestionnaire' => 'Gestionnaire',
            'structuration' => 'Structuration client',
            'promu_client' => 'Promu client',
            'region' => 'Région',
            'departement' => 'Département',
        ];
    }
}
