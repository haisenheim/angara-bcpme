<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProduitPhytoSanitaire extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'structuration_produits_phytosanitaires';
    protected $connection = 'central_app_mysql';

    public function type(){
        return $this->belongsTo('App\Models\Structuration\TypeProduitPhyto','type_id');
    }
}
