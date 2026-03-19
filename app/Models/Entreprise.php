<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $connection = 'central_app_mysql';

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function tiers(){
        return $this->hasMany('App\Models\Tier','entreprise_id');
    }

    public function reponses(){
        return $this->hasMany('App\Models\QuestionAnswer','entreprise_id');
    }

    public function answers(){
        return $this->hasMany('App\Models\Instruction\Scoring\Individual\DossierChoice','dossier_id');
    }

    public function dossiers(){
        return $this->hasMany('App\Models\Dossier','entreprise_id');
    }

    public function fichiers(){
        return $this->hasMany('App\Models\Fichier','entreprise_id');
    }

    public function produit(){
        return $this->belongsTo('App\Models\Produit'); //produit principale
    }

    public function produits(){
        return $this->belongsToMany('App\Models\Produit','entreprise_produits'); //autres produits
    }

    public function appuis(){
        return $this->belongsToMany('App\Models\Service','entreprise_appuis'); //autres produits
    }

    public function elements(){
        return $this->hasMany('App\Models\EntrepriseElementConstitutif','entreprise_id'); //autres produits
    }

    public function filiere(){
        return $this->belongsTo('App\Models\Filiere');
    }

    public function branche(){
        return $this->belongsTo('App\Models\Branche');
    }

    public function forme(){
        return $this->belongsTo('App\Models\Forme');
    }

    public function programmes(){
        return $this->belongsToMany('App\Models\Programme','dossiers');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }


    public function taille(){
        return $this->belongsTo('App\Models\Taille');
    }

    public function representation(){
        return $this->belongsTo('App\Models\Representation');
    }

    public function region(){
        return $this->belongsTo('App\Models\Region');
    }

    public function departement(){
        return $this->belongsTo('App\Models\Departement');
    }

    public function arrondissement(){
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function village(){
        return $this->belongsTo('App\Models\Village');
    }

    public function quartier(){
        return $this->belongsTo('App\Models\Quartier');
    }

    public function producteur(){
        return $this->belongsTo('App\Models\Structuration\Membre','producteur_id'); //autres produits
    }

    public function evaluationProfile(){
        return $this->hasOne(EntrepriseEvaluationProfile::class);
    }

    public function getTpersoAttribute(){
        if($this->personnel_mixte){
            return 'mixte';
        }
        if($this->personnel_permanent){
            return 'permanent';
        }
        if($this->personnel_saisonier){
            return 'saisonier';
        }
        return 'xxx';
    }
}
