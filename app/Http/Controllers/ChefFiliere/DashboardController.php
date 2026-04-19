<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ChefFiliere.dashboard', [
            'clientCount' => Entreprise::query()
                ->whereNotNull('promu_client_at')
                ->count(),
        ]);
    }
}
