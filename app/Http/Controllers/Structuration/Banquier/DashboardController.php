<?php

namespace App\Http\Controllers\Structuration\Banquier;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Structuration/Banquier/dashboard');
	}

}
