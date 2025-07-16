<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['dtn'];

    public function domaine()
    {
        return $this->belongsTo('App\Models\Domaine');
    }

    public function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function wallets(){
        return $this->hasMany('App\Models\Structuration\Wallet','cooperative_id');
    }

    public function caisses(){
        return $this->hasMany('App\Models\Structuration\Caisse','cooperative_id');
    }

    public function paiements(){
        return $this->hasMany('App\Models\Structuration\Paiement','cooperative_id');
    }

    public function entrepots(){
        return $this->hasMany('App\Models\Structuration\Entrepot','cooperative_id');
    }



    public function exploitants(){
        return $this->hasMany('App\Models\Structuration\Membre','cooperative_id');
    }

    public function agents(){
        return $this->hasMany('App\Models\Structuration\Agent','cooperative_id');
    }

    public function getPhotoAttribute(){
        $host = request()->getSchemeAndHttpHost();
        if($this->photo_uri){
            $path = $host.'/img/'.$this->photo_uri;
        }else{
            $path = $host.'/img/logo.png';
        }
        return $path;

    }

    public function getStockAttribute(){
        $items = $this->entrepots;
        return $items->reduce(function($c,$i){
            return $c + $i->stock;
        });
    }
}
