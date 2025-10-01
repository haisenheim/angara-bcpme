<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $guarded = [];
    //protected $table = 'operateur_mobiles_cooperatives';
    protected $connection = 'structuration_app_mysql';


    public function operateur()
    {
        return $this->belongsTo('App\Models\Structuration\Operateur','type_id');
    }

    public function entrepot()
    {
        return $this->belongsTo('App\Models\Structuration\Entrepot');
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
