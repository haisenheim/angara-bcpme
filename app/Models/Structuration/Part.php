<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    //
    protected $guarded = [];



    public function exploitant()
    {
        return $this->belongsTo('App\Models\Structuration\Membre');
    }

    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function paiements(){
        return $this->belongsToMany('App\Models\Structuration\Paiement','paiement_parts')->withPivot('montant','active','created_at','user_id');
    }

    public function getQuantityAttribute(){
        return $this->grd1_qty + $this->grd2_qty + $this->hs_qty;
    }

    public function getTotalAttribute(){
        return $this->grd1_qty*$this->item->grd1_price*1000 + $this->grd2_qty*$this->item->grd2_price*1000 + $this->hs_qty*$this->item->hs_price*1000;
    }

    public function getVersementAttribute(){
        $ps = $this->paiements;
        return $ps->reduce(function($c,$p){
            return $c + $p->pivot->montant;
        });
    }

    public function getResteAttribute(){
        return $this->total - $this->versement;
    }

}
