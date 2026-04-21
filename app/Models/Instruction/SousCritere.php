<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SousCritere extends Model
{
    use HasFactory;
    protected $table = 'sous_criteres';

    public function reponses()
    {
        return $this->hasMany(Reponse::class, 'critere_id');
    }

    public function critere()
    {
        return $this->belongsTo(Critere::class, 'critere_id');
    }

}
