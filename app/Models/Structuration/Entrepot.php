<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    //
    protected $guarded = [];


    public function stocks()
    {
        return $this->hasMany('App\Models\Structuration\EntrepotGamme','entrepot_id');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement');
    }
    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Cooperative');
    }

    public function getStockAttribute(){
        $stocks = $this->stocks;
        return $stocks->reduce(function($c,$i){
            return $c + $i->quantity;
        });
    }
}
