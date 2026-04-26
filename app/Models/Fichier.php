<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fichier extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $connection = 'central_app_mysql';

    protected $guarded = [];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function type()
    {
        return $this->belongsTo(FichierType::class, 'type_id');
    }

    public function entreprise()
    {
        return $this->belongsTo(Entreprise::class, 'entreprise_id');
    }

    public function dossier()
    {
        return $this->belongsTo(Dossier::class, 'dossier_id');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function getPathAttribute(): ?string
    {
        if (! $this->name) {
            return null;
        }

        return request()->getSchemeAndHttpHost().'/files/'.$this->name;
    }
}
