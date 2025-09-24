<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $connection = 'central_app_mysql';
    protected $guarded = [];

    public function arrondissement(){
        return $this->belongsTo(Arrondissement::class);
    }

    public function departement(){
        return $this->belongsTo(Departement::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }
}
