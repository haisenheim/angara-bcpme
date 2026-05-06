<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseSite extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $connection = 'central_app_mysql';

    protected $table = 'entreprise_sites';

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }

    public function arrondissement()
    {
        return $this->belongsTo(Arrondissement::class, 'arrondissement_id');
    }

    public function village()
    {
        return $this->belongsTo(Village::class, 'village_id');
    }

    public function quartier()
    {
        return $this->belongsTo(Quartier::class, 'quartier_id');
    }

    public function equipeMembres()
    {
        return $this->hasMany(EntrepriseEquipeMembre::class, 'entreprise_site_id');
    }
}

