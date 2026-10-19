<?php

namespace App\Services\Inertia;

use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Region;
use App\Models\Village;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Organisation du territoire (page commune admin, analyste, chef d'agence, gestionnaire, régional).
 * Arborescence région → département → arrondissement (même source que l'ancien endpoint util.localites,
 * LocaliteListResource) complétée des villages rattachés à chaque arrondissement.
 */
final class TerritoirePresenter
{
    public static function render(string $space): Response
    {
        $villages = Village::query()->orderBy('name')->get(['id', 'name', 'arrondissement_id'])->groupBy('arrondissement_id');
        $arrondissements = Arrondissement::query()->orderBy('name')->get(['id', 'name', 'departement_id'])->groupBy('departement_id');
        $departements = Departement::query()->orderBy('name')->get(['id', 'name', 'region_id'])->groupBy('region_id');

        $tree = Region::query()->orderBy('name')->get(['id', 'name'])->map(function (Region $r) use ($departements, $arrondissements, $villages) {
            $deps = ($departements[$r->id] ?? collect())->map(function ($d) use ($arrondissements, $villages) {
                $arrs = ($arrondissements[$d->id] ?? collect())->map(fn ($a) => [
                    'id' => 'a'.$a->id,
                    'name' => $a->name,
                    'level' => 'Arrondissement',
                    'children' => ($villages[$a->id] ?? collect())->map(fn ($v) => [
                        'id' => 'v'.$v->id, 'name' => $v->name, 'level' => 'Village', 'children' => [],
                    ])->values()->all(),
                ])->values();

                return ['id' => 'd'.$d->id, 'name' => $d->name, 'level' => 'Département', 'children' => $arrs->all()];
            })->values();

            return ['id' => 'r'.$r->id, 'name' => $r->name, 'level' => 'Région', 'children' => $deps->all()];
        })->values()->all();

        return Inertia::render('Territoire/Index', [
            'tree' => $tree,
            'totals' => [
                ['label' => 'Régions', 'value' => count($tree)],
                ['label' => 'Départements', 'value' => $departements->flatten()->count()],
                ['label' => 'Arrondissements', 'value' => $arrondissements->flatten()->count()],
                ['label' => 'Villages', 'value' => $villages->flatten()->count()],
            ],
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route($space.'.dashboard')],
                ['label' => 'Organisation du territoire'],
            ],
        ]);
    }
}
