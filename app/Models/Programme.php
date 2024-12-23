<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $table = 'programmes';
    protected $guarded = [];

    public function composantes(){
        return $this->hasMany('App\Models\Composante','programme_id');
    }

    public function users(){
        return $this->hasMany('App\Models\User','programme_id');
    }

    public function postes(){
        return $this->hasMany('App\Models\Poste','programme_id');
    }

    public function organismes(){
        return $this->hasMany('App\Models\ProgrammeOrganisme','programme_id');
    }

    public function resultats(){
        return $this->hasMany('App\Models\ProgrammeIndicateur','programme_id');
    }

    public function dossiers(){
        return $this->hasMany('App\Models\Dossier','programme_id');
    }



    public function produits(){
        return $this->belongsToMany('App\Models\Produit','programme_produits'); //autres produits
    }

    public function appuis(){
        return $this->belongsToMany('App\Models\Service','programme_appuis'); //autres produits
    }



    public function entreprises(){
        return $this->belongsToMany('App\Models\Entreprise','dossiers');
    }

    public function user(){
        return $this->belongsTo('App\Models\User');
    }

    public function getBudgetAttribute(){
        return $this->budget_af + $this->budget_anf + $this->budget_coord;
    }
}
