<?php

namespace App\Models\Structuration;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    //
    protected $guarded = [];
    //protected $table ='mouvements';

    public function entree()
    {
        return $this->belongsTo('App\Models\Structuration\Entree');
    }

    public function agent()
    {
        return $this->belongsTo('App\Models\Structuration\Agent');
    }

    

    public function mode()
    {
        return $this->belongsTo('App\Models\Structuration\ModePaiement','mode_paiement_id');
    }

    public function wallet()
    {
        return $this->belongsTo('App\Models\Structuration\Wallet','wallet_id');
    }

    public function caisse()
    {
        return $this->belongsTo('App\Models\Structuration\Caisse','caisse_id');
    }

    public function exploitant()
    {
        return $this->belongsTo('App\Models\Structuration\Membre');
    }

    public function cooperative()
    {
        return $this->belongsTo('App\Models\Structuration\Cooperative');
    }

    public function agence()
    {
        return $this->belongsTo('App\Models\Agence');
    }

    public function representation()
    {
        return $this->belongsTo('App\Models\Representation');
    }

    public function saison()
    {
        return $this->belongsTo('App\Models\Structuration\Saison');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getStatusAttribute()
    {
        if($this->caisse_id){
            return [
                'name'=>'validé',
                'class'=>'badge bg-success',
                'code'=>1
            ];
        }else{
            $data = [
                'name'=>'en attente',
                'class'=>'badge bg-warning',
                'code'=>0
            ];
            if($this->accepted_at){
                $data = [
                    'name'=>'validé',
                    'class'=>'badge bg-success',
                    'code'=>1
                ];
            }
            if($this->rejected_at){
                $data = [
                    'name'=>'rejeté',
                    'class'=>'badge bg-danger',
                    'code'=>-1
                ];
            }
            return $data;
        }

    }
}
