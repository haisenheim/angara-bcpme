<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secteur extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function agence(){
        return $this->belongsTo('App\Models\Agence');
    }
}
