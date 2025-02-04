<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class EntrepotGamme extends Model
{
    //
    protected $guarded = [];
    protected $table = 'entrepots_gammes';
    public $timestamps = false;

    public function entrepot()
    {
        return $this->belongsTo('App\Models\Entrepot','entrepot_id');
    }

    public function gamme()
    {
        return $this->belongsTo('App\Models\Gamme','gamme_id');
    }
}
