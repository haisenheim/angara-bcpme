<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;


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

    public function role(){
        return $this->belongsTo('App\Models\Role','role_id');
    }

    public function agence(){
        return $this->belongsTo('App\Models\Agence');
    }

    public function poste(){
        return $this->belongsTo('App\Models\Poste');
    }


    public function departement(){
        return $this->belongsTo('App\Models\Departement');
    }


    public function getStatusAttribute(){
        $data = [
            'name'=>'verrouillé',
            'color'=>'danger'
        ];
        if($this->active){
            $data = [
                'name'=>'actif',
                'color'=>'success'
            ];
        }

        return $data;
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
