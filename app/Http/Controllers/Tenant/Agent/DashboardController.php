<?php

namespace App\Http\Controllers\Tenant\Agent;

use App\Http\Controllers\ExtendedController;

class DashboardController extends ExtendedController
{



    public function index()
	{

		return view('Tenant/Agent/dashboard');
	}
}
