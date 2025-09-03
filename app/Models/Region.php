<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $connection = 'central_app_mysql';

    public function children(){
        return $this->hasMany('App\Models\Departement','region_id');
    }
}
