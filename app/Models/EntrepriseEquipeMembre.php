<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntrepriseEquipeMembre extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $connection = 'central_app_mysql';

    protected $table = 'entreprise_equipe_membres';

    protected function casts(): array
    {
        return [
            'date_naissance' => 'date',
            'associe' => 'bool',
            'dirigeant' => 'bool',
            'cni_expire_at' => 'date',
        ];
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function site()
    {
        return $this->belongsTo(EntrepriseSite::class, 'entreprise_site_id');
    }

    public function cniFichier()
    {
        return $this->belongsTo(Fichier::class, 'cni_fichier_id');
    }
}

