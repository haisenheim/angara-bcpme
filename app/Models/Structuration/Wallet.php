<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    //
    protected $guarded = [];
    protected $table = 'operateur_mobiles_cooperatives';

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function operateur()
    {
        return $this->belongsTo('App\Models\Structuration\Operateur');
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
