<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function programme(){
        return $this->belongsTo('App\Models\Programme');
    }

    public function gestionnaire(){
        return $this->belongsTo('App\Models\User','gestionnaire_id');
    }

    public function analyste(){
        return $this->belongsTo('App\Models\User','analyste_id');
    }

    public function agence(){
        return $this->belongsTo('App\Models\Agence','agence_id');
    }

    public function representation(){
        return $this->belongsTo('App\Models\Representation','represenantion_id');
    }

    public function indicateurs(){
        return $this->hasMany('App\Models\IndicateurFinancier','dossier_id');
    }

}
