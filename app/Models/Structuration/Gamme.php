<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Gamme extends Model
{
    //
    protected $guarded = [];

    public function dommaine(){
        return $this->belongsTo('App\Models\Domaine','domaine_id');
    }

}
