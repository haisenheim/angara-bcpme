<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;

    public function agences(){
        return $this->hasMany('App\Models\Agence');
    }

    public function children(){
        return $this->hasMany('App\Models\Arrondissement','departement_id');
    }

}
