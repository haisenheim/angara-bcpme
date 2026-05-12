<?php

namespace App\Services\Engagement;

use App\Exports\EngagementGridExport;
use App\Models\Entreprise;
use App\Services\TableDocumentExportService;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Génération des exports PDF / Excel de la grille des engagements.
 *
 * Le catalogue des rapports est défini dans config/engagements_exports.php.
 */
class EngagementExportService
{
    public function __construct(private readonly EngagementGridService $grid) {}

    /**
     * @return HttpResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(
        Entreprise $entreprise,
        string $format,
        ?int $partenaireId = null,
        string $search = '',
        string $reportId = 'grille_complete',
    ): mixed {
        $format = strtolower($format) === 'pdf' ? 'pdf' : 'xlsx';
        $this->definitionOrAbort($reportId, $format);

        return match ($reportId) {
            'grille_complete' => $this->downloadGrilleComplete($entreprise, $format, $partenaireId, $search),
            default => abort(501, 'Ce type d\'export n\'est pas encore implémenté.'),
        };
    }

    private function definitionOrAbort(string $reportId, string $format): void
    {
        $def = $this->findReport($reportId);
        if ($def === null) {
            abort(404, 'Type d\'export inconnu.');
        }
        if (empty($def['enabled'])) {
            abort(404, 'Export non disponible.');
        }
        if ($format === 'pdf' && empty($def['pdf'])) {
            abort(400, 'Ce rapport n\'est pas disponible en PDF.');
        }
        if ($format === 'xlsx' && empty($def['xlsx'])) {
            abort(400, 'Ce rapport n\'est pas disponible en Excel.');
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findReport(string $reportId): ?array
    {
        foreach (config('engagements_exports.reports', []) as $row) {
            if (($row['id'] ?? '') === $reportId) {
                return $row;
            }
        }

        return null;
    }

    private function downloadGrilleComplete(
        Entreprise $entreprise,
        string $format,
        ?int $partenaireId,
        string $search,
    ): mixed {
        $tree = $this->grid->buildTreeForEntreprise($entreprise->id, $partenaireId, $search);
        $stats = $this->grid->buildStatsForEntreprise($entreprise->id, $partenaireId, $search);

        $slug = preg_replace('/[^a-zA-Z0-9_-]/', '_', 'engagements_'.$entreprise->token) ?: 'engagements';
        $filename = $slug.'_grille-complete_'.now()->format('Y-m-d_His');

        if ($format === 'pdf') {
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('engagements.pdf.grid', [
                'entreprise' => $entreprise,
                'tree' => $tree,
                'stats' => $stats,
                'logoDataUri' => TableDocumentExportService::defaultLogoDataUri(),
                'generatedAt' => now()->format('d/m/Y H:i'),
            ]);
            $pdf->setPaper('a3', 'landscape');

            return $pdf->download($filename.'.pdf');
        }

        return Excel::download(
            new EngagementGridExport($entreprise, $tree, $stats),
            $filename.'.xlsx'
        );
    }
}
