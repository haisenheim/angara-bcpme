<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Operateur extends Model
{
    //
    protected $guarded = [];
    public $timestamps = false;
    protected $table = 'operateur_mobiles';
    protected $connection = 'central_app_mysql';

    protected $appends = ['photo'];

    public function requests(){
        return $this->hasMany('App\Models\Structuration\Request','operateur_id');
    }

    public function getPhotoAttribute(){
        $host = request()->getSchemeAndHttpHost();
        if($this->photo_uri){
            $path = $host.'/img/'.$this->photo_uri;
        }else{
            $path = $host.'/img/placeholder.png';
        }
        return $path;
    }

}
