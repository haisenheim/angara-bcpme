<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Regional/dashboard');
	}

}
