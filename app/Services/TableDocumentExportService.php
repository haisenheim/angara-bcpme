<?php

namespace App\Services;

use App\Exports\FormattedTableDocumentExport;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

class TableDocumentExportService
{
    public static function defaultLogoDataUri(): ?string
    {
        foreach (['img/logo-bcpme.png', 'img/logo.png'] as $rel) {
            $path = public_path($rel);
            if (! File::isReadable($path)) {
                continue;
            }
            $mime = str_ends_with(strtolower($path), '.png') ? 'image/png' : 'image/jpeg';

            return 'data:'.$mime.';base64,'.base64_encode((string) File::get($path));
        }

        return null;
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, string>  $headers
     */
    public static function downloadFormatted(
        array $rows,
        array $headers,
        string $format,
        string $filenameSlug,
        string $documentTitle,
        string $subtitle = '',
    ): mixed {
        $format = strtolower($format) === 'pdf' ? 'pdf' : 'xlsx';
        $safeSlug = preg_replace('/[^a-zA-Z0-9_-]/', '_', $filenameSlug) ?: 'export';
        $suffix = now()->format('Y-m-d_His');

        if ($format === 'pdf') {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('exports.formatted_table_pdf', [
                'title' => $documentTitle,
                'subtitle' => $subtitle,
                'headers' => $headers,
                'rows' => $rows,
                'logoDataUri' => self::defaultLogoDataUri(),
                'generatedAt' => now()->format('d/m/Y H:i'),
            ]);
            $pdf->setPaper('a4', 'landscape');

            return $pdf->download($safeSlug.'_'.$suffix.'.pdf');
        }

        return Excel::download(
            new FormattedTableDocumentExport($rows, $headers, $documentTitle, $subtitle),
            $safeSlug.'_'.$suffix.'.xlsx'
        );
    }
}
