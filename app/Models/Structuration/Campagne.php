<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Campagne extends Model
{
    //
    protected $guarded = [];
    //protected $table ='mouvements';

    public function verger()
    {
        return $this->belongsTo('App\Models\Structuration\Verger');
    }


    public function membre()
    {
        return $this->belongsTo('App\Models\Structuration\Membre','membre_id');
    }


    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }

    public function travaux(){
        return $this->hasMany('App\Models\Structuration\TravailVerger','campagne_id');
    }

    public function traitements(){
        return $this->hasMany('App\Models\Structuration\TraitementVerger','campagne_id');
    }

    public function visites(){
        return $this->hasMany('App\Models\Structuration\VisiteVerger','campagne_id');
    }

}
