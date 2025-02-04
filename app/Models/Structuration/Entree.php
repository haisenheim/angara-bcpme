<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    //
    protected $guarded = [];
    //protected $table ='mouvements';

    public function gamme()
    {
        return $this->belongsTo('App\Models\Structuration\Gamme');
    }

    public function entrepot()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot','entrepot_id');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Structuration\Agent');
    }

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Structuration\Exploitant');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function representation()
    {
        return $this->belongsTo('App\Models\Representation');
    }



    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }
}
