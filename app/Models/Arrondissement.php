<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function departement(){
        return $this->belongsTo('App\Models\Departement');
    }
}
