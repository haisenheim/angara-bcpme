<?php

namespace App\Http\Controllers\RoleSpace;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\EntreprisePieceExigible;

class DashboardController extends Controller
{
    use ResolvesRoleSpace;

    public function index()
    {
        $space = $this->resolveSpace();

        $stats = [
            'entreprises' => Entreprise::query()->count(),
            'dossiers' => Dossier::query()->count(),
            'pieces' => EntreprisePieceExigible::query()->whereNotNull('fichier_id')->count(),
        ];

        if ($space['route'] === 'respexp') {
            $stats['dossiers_a_affecter'] = Dossier::query()
                ->whereNull('analyste_id')
                ->count();
        }

        return view('RoleSpace.dashboard', [
            'space' => $space,
            'stats' => $stats,
        ]);
    }
}
