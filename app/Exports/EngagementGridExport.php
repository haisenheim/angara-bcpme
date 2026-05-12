<?php

namespace App\Exports;

use App\Models\Entreprise;
use App\Services\TableDocumentExportService;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * Excel hiérarchique de la grille des engagements d'une entreprise.
 * Reflète le visuel de la page Web et de l'export PDF.
 */
class EngagementGridExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  array<int, array<string, mixed>>  $tree
     * @param  array<string, mixed>  $stats
     */
    public function __construct(
        private readonly Entreprise $entreprise,
        private readonly array $tree,
        private readonly array $stats,
    ) {}

    public function view(): View
    {
        return view('engagements.pdf.grid_excel', [
            'entreprise' => $this->entreprise,
            'tree' => $this->tree,
            'stats' => $this->stats,
            'logoDataUri' => TableDocumentExportService::defaultLogoDataUri(),
            'generatedAt' => now()->format('d/m/Y H:i'),
        ]);
    }

    public function title(): string
    {
        $base = preg_replace('/[:\\\\\\/\\?\\*\\[\\]]/', '', (string) $this->entreprise->name) ?: 'Engagements';

        return mb_substr('Engagements — '.$base, 0, 31);
    }
}
