<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\User;

class DashboardController extends Controller
{
    use \App\Http\Controllers\Concerns\RendersInertiaDashboard;

    public function index()
    {
        return $this->renderInertiaDashboard('Tableau de bord administrateur', [
            ['key' => 'users', 'label' => 'Utilisateurs', 'icon' => 'group', 'href' => $this->routeIfExists('admin.users.index')],
            ['key' => 'entreprises', 'label' => 'Entreprises', 'icon' => 'storefront', 'href' => $this->routeIfExists('admin.entreprises.index')],
            ['key' => 'dossiers', 'label' => 'Dossiers d\'instruction', 'icon' => 'folder_shared', 'href' => $this->routeIfExists('admin.dossiers.index')],
        ], fn () => $this->getStats()->getData(true), null, [
            'subtitle' => 'Supervision globale de la plateforme ANGARA.',
            'plainLinks' => true,
            'widgets' => [[
                'key' => 'raccourcis', 'type' => 'links', 'title' => 'Administration', 'span' => 'full',
                'items' => array_values(array_filter([
                    $this->quickLink('Programmes d\'appui', 'volunteer_activism', 'admin.programmes.index'),
                    $this->quickLink('Utilisateurs', 'group', 'admin.users.index'),
                    $this->quickLink('Agences', 'apartment', 'admin.agences.index'),
                    $this->quickLink('Territoire', 'map', 'admin.territoire'),
                    $this->quickLink('Pièces exigibles', 'checklist', 'admin.pieces-exigibles.index'),
                    $this->quickLink('Délégation de pouvoir', 'gavel', 'admin.delegation-pouvoirs.index'),
                ])),
            ]],
        ]);
    }

    private function quickLink(string $label, string $icon, string $route): ?array
    {
        $href = $this->routeIfExists($route);

        return $href ? ['label' => $label, 'icon' => $icon, 'href' => $href] : null;
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
