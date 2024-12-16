<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function choices(){
        return $this->hasMany('App\Models\QuestionChoice','question_id');
    }
}
