<?php
namespace App\Exports\Gestionnaire\Cooperatives;

use App\Helpers\Colors;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaiementExport implements FromView, ShouldAutoSize, WithStyles, WithTitle
{

    protected $paiements;
    protected $cooperative;
    protected $colors;
    protected $start;
    protected $end;

    public function __construct($table,$cooperative,$start,$end)
    {
        $this->paiements = $table;
        $this->cooperative = $cooperative;
        $this->start = $start;
        $this->end = $end;
        $this->colors = Colors::getColors();
    }

    public function styles(Worksheet $sheet)
    {
        $rows = [];
        $sheet->getStyle('B1')
        ->getFill()
        ->applyFromArray(
            [
                'fillType' => 'solid','rotation' => 0,
                'color' => ['rgb' => $this->colors['white']],
                'font'=>['bold'=>true, 'size'=>18],
                'alignment'=>'center'
            ]
        );
        $sheet->getStyle('B1')
         ->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

         $sheet->getStyle('A3:I3')
         ->getFill()
         ->applyFromArray(
             [
                 'fillType' => 'solid','rotation' => 0,
                 'color' => ['rgb' => $this->colors['red']],
                 'font'=>['bold'=>true, 'size'=>16],
                 'alignment'=>'center'
             ]
         );
        //$sheet->getStyle(3)->getFont()->setColor(new Color($this->colors['white']));


        $rows[3] = [
            'font' => ['bold' => true, 'size'=>16],
            'color' => ['rgb' => $this->colors['white']],
        ];
        $rows['B1'] = [
            'font'=>['bold'=>true, 'size'=>18],
            'color' => ['rgb' => $this->colors['white']]
        ];
        return $rows;
        }


    public function view(): View
    {
        return view('Gestionnaire.Cooperatives.Exports.paiements', [
            'paiements' => $this->paiements,
            'cooperative'=> $this->cooperative,
            'start' => $this->start,
            'end' => $this->end,
        ]);
    }

    public function title(): string
    {
        return $this->cooperative->name . "_historique_paiements_" .  " ". $this->start."_". $this->end;
    }
}
