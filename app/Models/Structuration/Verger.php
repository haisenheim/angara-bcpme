<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Verger extends Model
{
    //
    protected $guarded = [];

    public function membre()
    {
        return $this->belongsTo('App\Models\Structuration\Membre');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function type()
    {
        return $this->belongsTo('App\Models\Type');
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getAgeAttribute(){
        return date('Y') - $this->annee;
    }
}
