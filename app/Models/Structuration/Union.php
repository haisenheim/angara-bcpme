<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Union extends Model
{
    //
    protected $guarded = [];

    public function cooperatives()
    {
        return $this->hasMany('App\Models\Tenant','union_id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\Structuration\Entrepot');
    }


    public function getStatusAttribute(){
        $data['color'] = 'danger';
        $data['code'] = 0;
        $data['name'] = 'bloqué';
        if($this->active){
            $data['color'] = 'success';
            $data['code'] = 1;
            $data['name'] = 'actif';
        }
        return $data;
    }

}
