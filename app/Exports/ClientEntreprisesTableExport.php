<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ClientEntreprisesTableExport implements FromView, ShouldAutoSize, WithTitle
{
    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  array<string, string>  $headers  clé ligne => libellé
     */
    public function __construct(
        private array $rows,
        private array $headers,
        private string $title,
    ) {}

    public function view(): View
    {
        return view('exports.client_entreprises_excel', [
            'rows' => $this->rows,
            'headers' => $this->headers,
        ]);
    }

    public function title(): string
    {
        return mb_substr(preg_replace('/[:\\\\\\/\\?\\*\\[\\]]/', '', $this->title) ?: 'Export', 0, 31);
    }
}
