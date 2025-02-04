<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class CooperativeOperateur extends Model
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
        $data['color'] = 'warning';
        $data['code'] = 0;
        $data['name'] = 'en attente';
        if($this->cancelled_at){
            $data['color'] = 'danger';
            $data['code'] = -1;
            $data['name'] = 'annulée';
        }
        if($this->validated_at){
            $data['color'] = 'success';
            $data['code'] = 1;
            $data['name'] = 'validée';
        }

        return $data;
    }

}
