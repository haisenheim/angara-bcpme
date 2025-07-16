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
        return $this->belongsTo('App\Models\User','agent_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Structuration\Membre','exploitant_id');
    }



    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function representation()
    {
        return $this->belongsTo('App\Models\Representation');
    }

    public function paiements(){
        return $this->hasMany('App\Models\Structuration\Paiement','entree_id');
    }


    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }

    public function getVersementsAttribute(){
        return $this->paiements->reduce(function($c,$item){
            return $c + $item->montant;
        });
    }

    public function getResteAttribute(){
        return $this->montant - $this->versements;
    }


}
