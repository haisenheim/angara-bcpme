<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class VisiteVerger extends Model
{
    //
    protected $guarded = [];
    protected $table ='visites_vergers';

    public function campagne()
    {
        return $this->belongsTo('App\Models\Structuration\Campagne');
    }
    
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

}
