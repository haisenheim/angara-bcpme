<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BanqueCooperative extends Model
{
    //
    protected $guarded = [];

    protected $table = 'tenants_banques';
    protected $connection = 'central_app_mysql';

    public function tenant()
    {
        return $this->belongsTo('App\Models\Tenant');
    }

    public function banque()
    {
        return $this->belongsTo('App\Models\Banque');
    }

}
