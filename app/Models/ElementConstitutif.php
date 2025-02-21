<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElementConstitutif extends Model  //Forme Juridique
{
    use HasFactory;
    //protected $table = 'entreprise_types';
    protected $table = 'elements_constitutifs_types';
    public $timestamps = false;
    protected $guarded = [];
}
