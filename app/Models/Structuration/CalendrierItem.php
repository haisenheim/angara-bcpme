<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CalendrierItem extends Model
{
    //
    protected $guarded = [];

    protected $table = 'calendrier_items';

    public function calendrier()
    {
        return $this->belongsTo('App\Models\Structuration\Calendrier');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }

    public function villages()
    {
        return $this->belongsToMany('App\Models\Village','calendrier_items_villages','item_id');
    }

    public function livraisons()
    {
        return $this->hasMany('App\Models\Structuration\Livraison','item_id');
    }

    public function prevision()
    {
        return $this->hasOne('App\Models\Structuration\Prevision','item_id');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement','arrondissement_id');
    }

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function getJourAttribute(){
        return \Carbon\Carbon::parse($this->day);
    }

}
