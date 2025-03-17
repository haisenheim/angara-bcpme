<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CritereProgrammePonderation extends Model
{
    use HasFactory;

    public function programme()
    {
        return $this->belongsTo('App\Models\Programme', 'programme_id');
    }

    public function critere()
    {
        return $this->belongsTo('App\Models\Instruction\SousCritere', 'critere_id');
    }
}
