<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Calendrier;
use App\Models\User;

class DashboardController extends ExtendedController
{



    public function index()
	{
        $user = User::find(auth()->user()->id);
        $calendrier = Calendrier::where('cooperative_id',auth()->user()->cooperative_id)->where('saison_id',$this->_saison->id)->first();
		return view('Cooperative/dashboard',compact('user','calendrier'));
	}
}
