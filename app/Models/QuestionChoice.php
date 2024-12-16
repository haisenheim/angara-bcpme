<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionChoice extends Model
{
    use HasFactory;

    protected $table = 'questions_choices';
    public $timestamps = false;
    protected $guarded = [];
}
