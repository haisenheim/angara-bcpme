<?php

namespace App\Models\Structuration;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    //
    protected $guarded = [];
    protected $dates = ['dtn','cni_expiration'];
    protected $table = 'producteurs';
    protected $connection = 'structuration_app_mysql';

    public function getAgeAttribute()
    {
        return Carbon::parse($this->attributes['dtn'])->age;
    }

    public function village()
    {
        return $this->belongsTo('App\Models\Village','village_id');
    }

    public function tenant()
    {
        return $this->belongsTo('App\Models\Tenant','tenant_id');
    }

    public function entite()
    {
        return $this->hasOne('App\Models\Entreprise','producteur_id');
    }

    public function liens(){
        return $this->hasMany('App\Models\Structuration\MembrePlateforme','exploitant_id');
    }

    public function vergers(){
        return $this->hasMany('App\Models\Structuration\Verger','membre_id');
    }

    public function niveau()
    {
        return $this->belongsTo('App\Models\Niveau');
    }

    public function situation()
    {
        return $this->belongsTo('App\Models\Structuration\Situation');
    }

    public function stocks(){
        return $this->hasMany('App\Models\Structuration\Entree','exploitant_id');
    }

    public function getMontantAttribute(){
        return $this->stocks->reduce(function($c,$item){
            return $c + $item->montant;
        });
    }

    public function getVersementsAttribute(){
        return $this->stocks->reduce(function($c,$item){
            return $c + $item->versements;
        });
    }

    public function getResteAttribute(){
        return $this->stocks->reduce(function($c,$item){
            return $c + $item->reste;
        });
    }


    public function paiements(){
        return $this->hasMany('App\Models\Structuration\Paiement','exploitant_id');
    }


    public  function getNameAttribute(){
        return $this->last_name . "  ".$this->first_name;
    }

    public function getPhotoAttribute(){
        $host = request()->getSchemeAndHttpHost();
        if($this->photo_uri){
            $path = $host.'/img/'.$this->photo_uri;
        }else{
            $path = $host.'/img/avatar.png';
        }
        return $path;

    }


}
