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
            'photo_uri'
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



}
