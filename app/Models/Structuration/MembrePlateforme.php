<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class MembrePlateforme extends Model
{
    //
    protected $guarded = [];
    protected $table = 'producteurs_plateformes';
    public $timestamps = false;
    protected $connection = 'structuration_app_mysql';

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Membre','exploitant_id');
    }

    public function plateforme()
    {
        return $this->belongsTo('App\Models\Plateforme','plateforme_id');
    }
}
