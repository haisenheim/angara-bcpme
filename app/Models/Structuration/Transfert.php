<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Transfert extends Model
{
    //
    protected $guarded = [];
    protected $connection = 'structuration_app_mysql';


    public function source()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot','source_id');
    }

    public function target()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot','target_id');
    }


    public function gamme()
    {
        return $this->belongsTo('App\Models\Structuration\Gamme','gamme_id');
    }


    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison','saison_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Structuration\User','user_id');
    }

    public function cooperativeSource()
    {
        return $this->belongsTo('App\Models\Tenant','tenant_source_id');
    }

    public function cooperativeTarget()
    {
        return $this->belongsTo('App\Models\Tenant','tenant_target_id');
    }


}
