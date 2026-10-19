<?php

namespace App\Services\Inertia\Admin;

use App\Models\User;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Liste des comptes utilisateurs vue par les profils d'encadrement (chef d'agence, DG, DGA, responsable régional).
 * Le périmètre (agence / direction de l'utilisateur connecté) est calculé par le contrôleur appelant ;
 * seules les actions d'activation / verrouillage existantes sont proposées.
 */
final class AgencyUsersPresenter
{
    public static function render(Collection $items, string $routePrefix): Response
    {
        $items->loadMissing(['role', 'agence']);

        $table = DataTable::make(K::singlePage($items))
            ->columns([
                ['key' => 'name', 'label' => 'Nom'],
                ['key' => 'phone', 'label' => 'Téléphone'],
                ['key' => 'email', 'label' => 'Email'],
                ['key' => 'role', 'label' => 'Rôle'],
                ['key' => 'agence', 'label' => 'Agence'],
                ['key' => 'direction', 'label' => 'Direction'],
                ['key' => 'status', 'label' => 'Statut'],
            ])
            ->row(fn (User $item) => [
                'cells' => [
                    'name' => ['title' => $item->name],
                    'phone' => $item->phone,
                    'email' => $item->email,
                    'role' => $item->role?->name,
                    'agence' => $item->agence ? $item->agence->name : '-',
                    'direction' => $item->representation?->name,
                    'status' => K::statusBadge($item->status),
                ],
                // Comptes sans jeton (anciens comptes) : routes d'activation inadressables.
                'actions' => filled($item->token) ? [
                    K::toggle((bool) $item->active, route($routePrefix.'.user.enable', $item->token), route($routePrefix.'.user.disable', $item->token)),
                ] : [],
            ])
            ->empty('Aucun compte utilisateur.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Comptes utilisateurs',
            'subtitle' => 'Liste des comptes utilisateurs',
            'countLabel' => 'compte(s)',
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route($routePrefix.'.dashboard')],
                ['label' => 'Utilisateurs'],
                ['label' => 'Liste des comptes utilisateurs'],
            ],
        ]);
    }
}
