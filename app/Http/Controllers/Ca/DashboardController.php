<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Ca/dashboard');
	}

}
