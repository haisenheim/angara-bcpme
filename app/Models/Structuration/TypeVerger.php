<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeVerger extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'structuration_types_vergers';
    protected $connection = 'central_app_mysql';

    public function produits(){
        return $this->hasMany('App\Models\Structuration\ProduitPhytosanitaire');
    }
}
