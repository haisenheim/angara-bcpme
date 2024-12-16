<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forme extends Model  //Forme Juridique
{
    use HasFactory;
    protected $table = 'entreprise_types';
    public $timestamps = false;
    protected $guarded = [];
}
