<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Prevision extends Model
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
        return $this->belongsTo('App\Models\User','agent_id');
    }

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Structuration\Membre','membre_id');
    }

    public function entrees(){
        return $this->hasMany('App\Models\Structuration\Entree','prevision_id');
    }

    public function getTotalAttribute(){
        return $this->entrees->reduce(function($c,$i){
            return $c+$i->quantity;
        });
    }

    public function getResteAttribute(){
        return $this->quantity - $this->total;
    }




    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }




}
