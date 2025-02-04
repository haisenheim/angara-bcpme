<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $guarded = [];
    protected $table = 'paiements_requests_cooperatives';

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function operateur()
    {
        return $this->belongsTo('App\Models\Structuration\Operateur');
    }

    public function getStatusAttribute(){
        $data = [
            'name'=>'en attente',
            'code'=>0,
            'color'=>'warning'
        ];

        if($this->cancelled_at){
            $data = [
                'name'=>'rejetée',
                'code'=>-1,
                'color'=>'danger'
            ];
        }

        if($this->validated_at){
            $data = [
                'name'=>'approuvée',
                'code'=>-1,
                'color'=>'success'
            ];
        }

        return $data;

    }


}
