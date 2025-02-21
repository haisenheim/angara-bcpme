<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseElementConstitutif extends Model  //Forme Juridique
{
    use HasFactory;
    //protected $table = 'entreprise_types';
    protected $table = 'entreprises_elements_constitutifs';
    public $timestamps = false;
    protected $guarded = [];

    public function entreprise(){
        return $this->belongsTo('App\Models\Entreprise','entreprise_id');
    }

    public function type(){
        return $this->belongsTo('App\Models\ElementConstitutif','type_id');
    }

    public function getPathAttribute(){
        $host = request()->getSchemeAndHttpHost();
        if($this->uri){
            $path = $host.'/files/'.$this->uri;
            return $path;
        }
        return null;
    }
}
