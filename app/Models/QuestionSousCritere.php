<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionSousCritere extends Model
{
    use HasFactory;
    protected $table = 'questions_sous_criteres';
    public $timestamps = false;
    protected $guarded = [];

    public function questions(){
        return $this->hasMany('App\Models\Question','sous_critere_id');
    }

    public function critere()
    {
        return $this->belongsTo(Instruction\Critere::class, 'critere_id');
    }

   
}
