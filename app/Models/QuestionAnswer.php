<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    use HasFactory;
    protected $table = 'questions_answers';
    protected $guarded = [];

    public function question(){
        return $this->belongsTo('App\Models\Question');
    }

    public function choice(){
        return $this->belongsTo('App\Models\QuestionChoice','choice_id');
    }
}
