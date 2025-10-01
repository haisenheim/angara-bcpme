<?php

namespace App\Models\Structuration;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
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
        return $this->belongsTo('App\Models\Structuration\Role','role_id');
    }

    public function entrepot(){
        return $this->belongsTo('App\Models\Structuration\Entrepot');
    }

    public function caisse(){
        return $this->hasOne('App\Models\Structuration\Caisse','caissier_id');
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
