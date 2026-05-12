<?php

namespace Modules\Simulator\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ScheduleExport implements FromArray, ShouldAutoSize, WithHeadings, WithStyles, WithTitle
{
    public function __construct(private readonly SimulationResult $result) {}

    public static function download(SimulationResult $result, string $filename): BinaryFileResponse
    {
        return Excel::download(new self($result), $filename);
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->result->lines as $line) {
            $rows[] = [
                $line->periodIndex,
                $line->periodDate?->format('Y-m-d') ?? '',
                round($line->capitalDueStart, 2),
                round($line->principalPaid, 2),
                round($line->interestPaid, 2),
                round($line->insurancePaid, 2),
                round($line->feesPaid, 2),
                round($line->vatPaid, 2),
                round($line->totalPayment, 2),
                round($line->capitalDueEnd, 2),
                $line->isDeferred ? 'Oui' : 'Non',
            ];
        }

        $rows[] = [
            'TOTAL',
            '',
            '',
            round($this->result->totalPrincipal, 2),
            round($this->result->totalInterest, 2),
            round($this->result->totalInsurance, 2),
            round($this->result->totalFees, 2),
            round($this->result->totalVat, 2),
            round($this->result->totalDue, 2),
            '',
            '',
        ];

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Periode',
            'Date',
            'Capital du debut',
            'Capital rembourse',
            'Interets',
            'Assurance',
            'Frais',
            'TVA',
            'Echeance totale',
            'Capital du fin',
            'Differe',
        ];
    }

    public function title(): string
    {
        return 'Echeancier';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
            (count($this->result->lines) + 2) => ['font' => ['bold' => true]],
        ];
    }
}
