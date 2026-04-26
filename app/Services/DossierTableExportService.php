<?php

namespace App\Services;

use App\Models\Dossier;
use Illuminate\Support\Collection;

class DossierTableExportService
{
    /**
     * @param  Collection<int, Dossier>|iterable<Dossier>  $dossiers
     * @return array<int, array<string, mixed>>
     */
    public static function rowsRoleSpace(iterable $dossiers): array
    {
        $out = [];
        foreach ($dossiers as $d) {
            $out[] = [
                'entreprise' => $d->entreprise?->name ?? '',
                'programme' => method_exists($d, 'programmesLabel') ? ($d->programmesLabel() ?: ($d->programme?->name ?? '')) : ($d->programme?->name ?? ''),
                'analyste' => $d->analyste?->name ?? '',
                'gestionnaire' => $d->gestionnaire?->name ?? '',
                'etat' => $d->status['name'] ?? '',
                'created_at' => optional($d->created_at)->format('d/m/Y H:i') ?? '',
            ];
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function headersRoleSpace(): array
    {
        return [
            'entreprise' => 'Entreprise',
            'programme' => 'Programme',
            'analyste' => 'Analyste',
            'gestionnaire' => 'Gestionnaire',
            'etat' => 'État',
            'created_at' => 'Créé le',
        ];
    }

    public static function download(array $rows, string $format, string $filenameSlug, string $documentTitle, string $subtitle = ''): mixed
    {
        return TableDocumentExportService::downloadFormatted(
            $rows,
            self::headersRoleSpace(),
            $format,
            $filenameSlug,
            $documentTitle,
            $subtitle,
        );
    }
}

