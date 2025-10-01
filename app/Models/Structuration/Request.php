<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $guarded = [];
    protected $connection = 'structuration_app_mysql';

    public function tenant()
    {
        return $this->belongsTo('App\Models\Tenant');
    }

    public function operateur()
    {
        return $this->belongsTo('App\Models\Structuration\Operateur');
    }

    public function wallet()
    {
        return $this->belongsTo('App\Models\Structuration\Wallet','wallet_id');
    }

    public function caisse()
    {
        return $this->belongsTo('App\Models\Structuration\Caisse');
    }

    public function compte()
    {
        return $this->belongsTo('App\Models\Structuration\BanqueCooperative','source_id');
    }
    public function banque()
    {
        return $this->belongsTo('App\Models\Banque');
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
                'name'=>'approuvée et non traitée',
                'code'=>1,
                'color'=>'success'
            ];
        }

        if($this->treated_at){
            $data = [
                'name'=>'traitée',
                'code'=>2,
                'color'=>'dark'
            ];
        }



        return $data;

    }


}
