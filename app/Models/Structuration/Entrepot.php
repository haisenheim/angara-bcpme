<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    //
    protected $guarded = [];

    public function livraisons()
    {
        return $this->hasMany('App\Models\OrderItem','entrepot_source_id');
    }

    public function receptions()
    {
        return $this->hasMany('App\Models\OrderItem','entrepot_target_id');
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
}
