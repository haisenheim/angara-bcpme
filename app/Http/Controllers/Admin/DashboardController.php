<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('Admin/dashboard');
    }

    public function getStats()
    {
        return response()->json([
            'users' => User::query()->count(),
            'entreprises' => Entreprise::query()->count(),
            'dossiers' => Dossier::query()->count(),
        ]);
    }
}
