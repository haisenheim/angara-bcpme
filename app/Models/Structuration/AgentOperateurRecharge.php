<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class AgentOperateurRecharge extends Model
{
    //
    protected $guarded = [];
    protected $table = 'operateur_mobiles_agents_recharges';

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function operateur()
    {
        return $this->belongsTo('App\Models\Structuration\Operateur');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Structuration\Agent');
    }

    public function wallet()
    {
        return $this->belongsTo('App\Models\Structuration\AgentOperateur','wallet_id');
    }

    public function getStatusAttribute(){
        $data['color'] = 'success';
        $data['code'] = 1;
        $data['name'] = 'actif';
        if(!$this->active){
            $data['color'] = 'danger';
            $data['code'] = 0;
            $data['name'] = 'verouillé';
        }
        return $data;
    }

}
