<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // protected $connection = 'central_app_mysql';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'token',
        'active',
        'agence_id',
        'organisation_type',
        'organisation_entite_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function organisationEntite()
    {
        return $this->belongsTo(OrganisationEntite::class, 'organisation_entite_id');
    }

    public function poste()
    {
        return $this->belongsTo('App\Models\Poste');
    }

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
    }

    public function getStatusAttribute()
    {
        $data = [
            'name' => 'verrouillé',
            'color' => 'danger',
        ];
        if ($this->active) {
            $data = [
                'name' => 'actif',
                'color' => 'success',
            ];
        }

        return $data;
    }

    public function getPhotoAttribute()
    {
        $host = request()->getSchemeAndHttpHost();
        if ($this->photo_uri) {
            $path = $host.'/img/'.$this->photo_uri;
        } else {
            $path = $host.'/img/avatar.png';
        }

        return $path;

    }

    /**
     * Profil d’instruction des dossiers (profils.id 17) : synonymes « analyste financier »,
     * « analyste financier d’exploitation » (AFE), « analyste » (côté instruction).
     * Rattachement agence optionnel.
     *
     * @see config('angara.role_analyste_financier')
     */
    public function isAnalysteFinancierExploitation(): bool
    {
        return (int) ($this->role_id ?? 0) === (int) config('angara.role_analyste_financier', 17);
    }

    /** Équivalent à isAnalysteFinancierExploitation() — libellé métier « analyste financier ». */
    public function isAnalysteFinancier(): bool
    {
        return $this->isAnalysteFinancierExploitation();
    }

    /**
     * Même profil 17, sans rattachement agence (périmètre dossiers banque entière côté liste / dashboard analyste).
     */
    public function isAnalysteFinancierNational(): bool
    {
        return $this->isAnalysteFinancierExploitation() && $this->agence_id === null;
    }
}
