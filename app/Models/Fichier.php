<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    public function type(){
        return $this->belongsTo('App\Models\FichierType','type_id');
    }

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise','entreprise_id');
    }

    public function getPathAttribute(): ?string
    {
        if (! $this->name) {
            return null;
        }

        return request()->getSchemeAndHttpHost().'/files/'.$this->name;
    }
}
