<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agence extends Model
{
    //
    protected $guarded = [];
    public $timestamps = false;

    public function representation()
    {
        return $this->belongsTo('App\Models\Representation');
    }

   

    public function departement()
    {
        return $this->belongsTo('App\Models\Departement');
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
}
