<?php

namespace App\Services\Inertia\Admin;

use App\Models\Secteur;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Pages Inertia des secteurs coopératifs (admin et gestionnaire) : liste et fiche.
 * Le périmètre (tous les secteurs / secteurs de l'agence) est fourni par le contrôleur.
 */
final class SecteurPagePresenter
{
    /**
     * @param  string  $space  admin | gestionnaire
     */
    public static function index(Collection $items, string $space, array $actions = [], ?string $notice = null): Response
    {
        $table = DataTable::make(K::singlePage($items))
            ->columns([
                ['key' => 'name', 'label' => 'Secteur'],
                ['key' => 'agence', 'label' => 'Agence'],
                ['key' => 'contacts', 'label' => 'Contacts'],
            ])
            ->row(fn (Secteur $item) => [
                'href' => route($space.'.secteurs.show', $item->token),
                'cells' => [
                    'name' => $item->name,
                    'agence' => $item->agence?->name,
                    'contacts' => $item->contacts,
                ],
            ])
            ->empty('Aucun secteur.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Secteurs',
            'subtitle' => 'Liste de tous les secteurs coopératifs',
            'notice' => $notice,
            'countLabel' => 'secteur(s)',
            'table' => $table->toArray(),
            'actions' => $actions,
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route($space.'.dashboard')],
                ['label' => 'Secteurs'],
                ['label' => 'Liste des secteurs'],
            ],
        ]);
    }

    public static function show(?Secteur $item, string $space): Response
    {
        abort_if($item === null, 404);

        $coops = collect($item->cooperatives ?? []);

        return Inertia::render('Admin/Fiche', [
            'title' => (string) $item->name,
            'subtitle' => 'Détails',
            'sections' => [
                [
                    'title' => 'Secteur',
                    'span' => 1,
                    'items' => [
                        ['label' => 'Nom', 'value' => $item->name],
                        ['label' => 'Localisation', 'value' => $item->localisation],
                        ['label' => 'Contacts', 'value' => $item->contacts, 'multiline' => true],
                        ['label' => 'Agence de tutelle', 'value' => $item->agence?->name],
                    ],
                ],
                [
                    'title' => 'Organisations rattachées',
                    'span' => 2,
                    'table' => [
                        'columns' => [
                            ['key' => 'name', 'label' => 'Organisation'],
                            ['key' => 'phone', 'label' => 'Téléphone'],
                            ['key' => 'arrondissement', 'label' => 'Arrondissement'],
                            ['key' => 'departement', 'label' => 'Département'],
                            ['key' => 'region', 'label' => 'Région'],
                        ],
                        'rows' => $coops->map(fn ($c) => [
                            'name' => $c->name,
                            'phone' => $c->phone,
                            'arrondissement' => $c->arrondissement?->name,
                            'departement' => $c->departement?->name,
                            'region' => $c->region?->name,
                        ])->values()->all(),
                    ],
                    'empty' => 'Aucune organisation rattachée.',
                ],
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route($space.'.dashboard')],
                ['label' => 'Secteurs coopératifs', 'href' => route($space.'.secteurs.index')],
                ['label' => (string) $item->name],
            ],
        ]);
    }
}
