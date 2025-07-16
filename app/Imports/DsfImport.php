<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class DsfImport implements WithMultipleSheets
{


    public function sheets(): array
    {
        return [
            0 => new BilanImport(),
            1 => new ResultatImport(),
            3 => new FluxTresoImport(),
        ];
    }
}
