<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tier extends Model
{
    //

    protected $guarded = [];
    public $timestamps = false;

    public function lien(){
        return $this->belongsTo('App\Models\Lien');
    }

    public function person(){
        return $this->belongsTo('App\Models\Person');
    }

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function company(){
        return $this->belongsTo('App\Models\Entreprise','company_id');
    }


}
