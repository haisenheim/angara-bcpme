<?php

namespace App\Models\Instruction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;

    public function souscriteres()
    {
        return $this->hasMany(SousCritere::class, 'critere_id');
    }

    
}
