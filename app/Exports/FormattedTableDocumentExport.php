<?php

namespace App\Exports;

use App\Services\TableDocumentExportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FormattedTableDocumentExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, string>  $headers
     */
    public function __construct(
        private array $rows,
        private array $headers,
        private string $title,
        private string $subtitle,
    ) {}

    public function view(): View
    {
        return view('exports.formatted_table_excel', [
            'rows' => $this->rows,
            'headers' => $this->headers,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'logoDataUri' => TableDocumentExportService::defaultLogoDataUri(),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function title(): string
    {
        return mb_substr(preg_replace('/[:\\\\\\/\\?\\*\\[\\]]/', '', $this->title) ?: 'Export', 0, 31);
    }
}
