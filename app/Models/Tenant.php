<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'token',
            'user_id',
            'name',
            'address',
            'phone',
            'region_id',
            'entreprise_id',
            'domaine_id',
            'agence_id',
            'secteur_id',
            'representation_id',
            'arrondissement_id',
            'active',
            'token',
            'departement_id',
            'photo_uri',
            'union_id',
            'is_union'
        ];
    }

    public function getIncrementing()
    {
        return true;
    }

        protected $dates = ['dtn'];

    public function domaine()
    {
        return $this->belongsTo('App\Models\Domaine');
    }

    public function secteur()
    {
        return $this->belongsTo('App\Models\Secteur');
    }

    public function entreprise()
    {
        return $this->belongsTo('App\Models\Entreprise');
    }
    public function parent()
    {
        return $this->belongsTo('App\Models\Tenant','union_id');
    }
    public function children()
    {
        return $this->hasMany('App\Models\Tenant','union_id');
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function arrondissement()
    {
        return $this->belongsTo('App\Models\Arrondissement');
    }

    public function comptes()
    {
        return $this->hasMany('App\Models\Structuration\BanqueCooperative','tenant_id');
    }

    public function entrepots()
    {
        return $this->hasMany('App\Models\Structuration\Entrepot','tenant_id');
    }

    public function membres()
    {
        return $this->hasMany('App\Models\Structuration\Membre','tenant_id');
    }

    public function caisses()
    {
        return $this->hasMany('App\Models\Structuration\Caisse','tenant_id');
    }

    public function wallets()
    {
        return $this->hasMany('App\Models\Structuration\Wallet','tenant_id');
    }

    public function requests()
    {
        return $this->hasMany('App\Models\Structuration\Request','tenant_id');
    }

}
