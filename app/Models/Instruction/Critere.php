<?php

namespace App\Models\Instruction;

use App\Models\QuestionSousCritere;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    use HasFactory;

    public function souscriteres()
    {
        return $this->hasMany(SousCritere::class, 'critere_id');
    }

    /** Sections du questionnaire d’entrée en relation (table questions_sous_criteres). */
    public function questionnaireSousCriteres()
    {
        return $this->hasMany(QuestionSousCritere::class, 'critere_id')->orderBy('id');
    }
}
