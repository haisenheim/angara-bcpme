<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Sortie extends Model
{
    //
    protected $guarded = [];
    //protected $table ='mouvements';
    protected $connection = 'structuration_app_mysql';
    
    public function gamme()
    {
        return $this->belongsTo('App\Models\Structuration\Gamme');
    }

    public function source()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot','entrepot_source_id');
    }

    public function target()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot','entrepot_target_id');
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

    public function offtaker()
    {
        return $this->belongsTo('App\Models\Client','offtaker_id');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }
}
