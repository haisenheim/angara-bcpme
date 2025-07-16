<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeProduitPhyto extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'structuration_types_produits';
    protected $connection = 'central_app_mysql';

    public function produits(){
        return $this->hasMany('App\Models\Structuration\ProduitPhytosanitaire');
    }
}
