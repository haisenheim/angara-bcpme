<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Tableau de bord du responsable régional', [
            ['key' => 'entreprises', 'label' => 'Entreprises (région)', 'icon' => 'storefront', 'href' => $this->routeIfExists('regional.entreprises.index')],
            ['key' => 'dossiers', 'label' => 'Dossiers', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('regional.dossiers.index')],
            ['key' => 'prospects', 'label' => 'Prospects soumis', 'icon' => 'person_search', 'href' => $this->routeIfExists('regional.entreprises.prospects')],
        ], fn () => $this->getStats()->getData(true), null, [
            'subtitle' => 'Vue d\'ensemble de l\'activité de votre région.',
            'plainLinks' => true,
        ]);
    }

    public function getStats()
    {
        $rid = (int) auth()->user()->representation_id;

        return response()->json([
            'entreprises' => Entreprise::query()->where('representation_id', $rid)->count(),
            'dossiers' => Dossier::query()->where('representation_id', $rid)->count(),
            'prospects' => Entreprise::query()
                ->where('representation_id', $rid)
                ->where('prospect', true)
                ->whereNotNull('prospect_submitted_at')
                ->count(),
        ]);
    }
}
