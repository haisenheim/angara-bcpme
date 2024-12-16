<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contrat;
use App\Models\Cooperative;
use App\Models\Livraison;
use App\Models\Protocole;
use App\Models\Saison;
use App\Models\Secteur;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
	{
		return view('Analyste/dashboard');
	}

}
