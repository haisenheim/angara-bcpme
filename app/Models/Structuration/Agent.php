<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $guarded = [];
    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
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
