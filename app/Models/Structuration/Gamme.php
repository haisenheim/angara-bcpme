<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Gamme extends Model
{
    //
    protected $guarded = [];
    protected $connection = 'central_app_mysql';

    public function dommaine(){
        return $this->belongsTo('App\Models\Domaine','domaine_id');
    }

}
