<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Calendrier extends Model
{
    //
    protected $guarded = [];

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function items()
    {
        return $this->hasMany('App\Models\Structuration\CalendrierItem','calendrier_id');
    }



}
