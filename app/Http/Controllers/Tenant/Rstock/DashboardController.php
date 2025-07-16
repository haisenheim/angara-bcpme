<?php

namespace App\Http\Controllers\Tenant\Rstock;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Wallet;
use App\Models\User;

class DashboardController extends ExtendedController
{

    public function index()
	{
        $item = Entrepot::find(auth()->user()->entrepot_id);
		return view('Tenant/Rstock/dashboard',compact('item'));
	}
}
