<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicateurFinancier extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'indicateurs_financiers';

}
