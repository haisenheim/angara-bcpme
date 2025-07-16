<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Wallet;
use App\Models\User;

class DashboardController extends ExtendedController
{



    public function index()
	{
        $user = User::find(auth()->user()->id);
        $entrepots = Entrepot::all();
        $exploitants = Membre::all();
        $agents = User::where('role_id',2)->get();
        $wallets = Wallet::all();
        //$calendrier = Calendrier::where('cooperative_id',auth()->user()->cooperative_id)->where('saison_id',$this->_saison->id)->first();
		return view('Tenant/Admin/dashboard',compact('user','entrepots','agents','exploitants','wallets'));
	}
}
