<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Composante extends Model
{
    use HasFactory;
    protected $table = 'composantes';
    public $timestamps = false;
    protected $guarded = [];
}
