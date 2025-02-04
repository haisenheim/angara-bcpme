<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Saison extends Model
{
    //
    protected $guarded = [];
    public $timestamps = false;
    protected $dates = ['start','end'];


    public function calendriers(){
        return $this->hasMany('App\Models\Calendrier');
    }

    public function getStatusAttribute(){
        $data = [
            'name'=>'en cours',
            'color'=>'success'
        ];

        if($this->closed_at){
            $data = [
                'name'=>'close',
                'color'=>'warning'
            ];
        }

        if(!$this->active){
            $data = [
                'name'=>'annulé',
                'color'=>'danger'
            ];
        }

        return $data;
    }

    protected  $casts = [

        ];

}
