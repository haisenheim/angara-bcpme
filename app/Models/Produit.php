<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function children(){
        return $this->hasMany('App\Models\Produit','parent_id');
    }
}
