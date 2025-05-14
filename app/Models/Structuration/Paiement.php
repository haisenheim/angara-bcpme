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

    public function compte()
    {
        return $this->belongsTo('App\Models\Structuration\AgentOperateur','agent_wallet_id');
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
        return $this->belongsTo('App\Models\Structuration\Exploitant');
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
}
