<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class MembrePlateforme extends Model
{
    //
    protected $guarded = [];
    protected $table = 'membres_plateformes';
    public $timestamps = false;

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Membre','exploitant_id');
    }

    public function plateforme()
    {
        return $this->belongsTo('App\Models\Plateforme','plateforme_id');
    }
}
