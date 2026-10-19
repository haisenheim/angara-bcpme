<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisme;
use App\Models\Torganisme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class OrganismeController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'type_id' => (string) $request->query('type_id', ''),
        ];

        $items = Organisme::query()
            ->with(['type', 'parent'])
            ->when($filters['q'] !== '', function (Builder $q) use ($filters) {
                $q->where(function (Builder $sub) use ($filters) {
                    $sub->where('name', 'like', '%'.$filters['q'].'%')
                        ->orWhere('abb', 'like', '%'.$filters['q'].'%');
                });
            })
            ->when($filters['type_id'] !== '', fn (Builder $q) => $q->where('type_id', (int) $filters['type_id']))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $ref = $this->referenceData();

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Nom'],
                ['key' => 'abb', 'label' => 'Abrév.'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'parent', 'label' => 'Parent'],
            ])
            ->row(fn (Organisme $item) => [
                'href' => route('admin.organismes.show', $item->id),
                'cells' => [
                    'name' => $item->name,
                    'abb' => $item->abb ?: '-',
                    'type' => $item->type?->name ?? '-',
                    'parent' => $item->parent?->name ?? '-',
                ],
                'actions' => [
                    K::link('show', 'Afficher', route('admin.organismes.show', $item->id)),
                    K::destroy(route('admin.organismes.destroy', $item->id), 'Supprimer cet organisme ?'),
                ],
            ])
            ->search('Rechercher (nom, abréviation)...')
            ->select('type_id', 'Type', $ref['types']->pluck('name', 'id'), 'Tous les types')
            ->empty('Aucun organisme');

        return Inertia::render('Admin/Crud', [
            'title' => 'Organismes',
            'subtitle' => 'Liste des bailleurs et partenaires de financement',
            'countLabel' => 'organisme(s)',
            'table' => $table->toArray(),
            'actions' => [
                K::dialog('create', 'Ajouter un organisme', 'Nouvel organisme', $this->formFields($ref), route('admin.organismes.store'), 'post', ['wide' => true]),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Paramètres'],
                ['label' => 'Organismes'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Organisme::create($data);
        Session::flash('success', 'Organisme créé avec succès.');

        return redirect()->route('admin.organismes.index');
    }

    public function show(Organisme $organisme)
    {
        $organisme->load(['type', 'parent', 'children']);

        $item = $organisme;

        return Inertia::render('Admin/Fiche', [
            'title' => $item->name,
            'subtitle' => "Détails de l'organisme",
            'sections' => [
                [
                    'title' => 'Organisme',
                    'span' => 2,
                    'items' => [
                        ['label' => 'Nom', 'value' => $item->name],
                        ['label' => 'Abréviation', 'value' => $item->abb ?: '-'],
                        ['label' => 'Type', 'value' => $item->type?->name ?? '-'],
                        ['label' => 'Organisme parent', 'value' => $item->parent?->name ?? '-',
                            'href' => $item->parent ? route('admin.organismes.show', $item->parent->id) : null],
                    ],
                ],
                [
                    'title' => 'Organismes rattachés',
                    'span' => 1,
                    'links' => $item->children->map(fn ($c) => ['label' => $c->name, 'href' => route('admin.organismes.show', $c->id)])->values()->all(),
                    'empty' => 'Aucun organisme rattaché.',
                ],
            ],
            'actions' => [
                K::dialog('edit', 'Modifier', "Modifier l'organisme", $this->formFields($this->referenceData(), $item), route('admin.organismes.update', $item->id), 'put', ['wide' => true]),
            ],
            'dangerActions' => [
                K::destroy(route('admin.organismes.destroy', $item->id), 'Supprimer cet organisme ?', 'Supprimer cet organisme'),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Organismes', 'href' => route('admin.organismes.index')],
                ['label' => $item->name],
            ],
        ]);
    }

    public function update(Request $request, Organisme $organisme)
    {
        $data = $this->validateData($request, $organisme->id);
        $organisme->update($data);
        Session::flash('success', 'Organisme mis à jour avec succès.');

        return redirect()->route('admin.organismes.show', $organisme->id);
    }

    public function destroy(Organisme $organisme)
    {
        $organisme->delete();
        Session::flash('success', 'Organisme supprimé.');

        return redirect()->route('admin.organismes.index');
    }

    private function formFields(array $ref, ?Organisme $item = null): array
    {
        $parents = $ref['parents']->when($item, fn ($c) => $c->where('id', '!=', $item->id))->pluck('name', 'id');
        $fields = [
            K::field('name', 'Nom', 'text', ['required' => true, 'value' => $item?->name ?? '']),
            K::field('abb', 'Abréviation', 'text', ['value' => $item?->abb ?? '']),
            K::field('type_id', 'Type', 'select', ['required' => true, 'value' => $item ? (string) $item->type_id : '',
                'options' => K::options($ref['types']->pluck('name', 'id'))]),
            K::field('parent_id', $item ? 'Organisme parent' : 'Organisme parent (facultatif)', 'select', [
                'value' => (string) ($item?->parent_id ?? 0), 'options' => K::options($parents, ['0' => 'Aucun'])]),
        ];
        if ($ref['pays']->isNotEmpty()) {
            $fields[] = K::field('pay_id', $item ? 'Pays' : 'Pays (facultatif)', 'select', [
                'value' => (string) ($item?->pay_id ?? 0), 'options' => K::options($ref['pays']->pluck('name', 'id'), ['0' => 'Aucun'])]);
        }

        return $fields;
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'abb' => ['nullable', 'string', 'max:20'],
            'type_id' => ['required', 'integer', Rule::exists((new Torganisme)->getTable(), 'id')],
            'parent_id' => ['nullable', 'integer'],
            'pay_id' => ['nullable', 'integer'],
        ];

        $validated = $request->validate($rules);

        $validated['parent_id'] = $validated['parent_id'] ?? 0;
        $validated['pay_id'] = $validated['pay_id'] ?? 0;

        if ($ignoreId !== null && (int) ($validated['parent_id'] ?? 0) === $ignoreId) {
            $validated['parent_id'] = 0;
        }

        return $validated;
    }

    private function referenceData(): array
    {
        return [
            'types' => Torganisme::orderBy('name')->get(),
            'parents' => Organisme::orderBy('name')->get(['id', 'name']),
            'pays' => Schema::hasTable('pays')
                ? DB::table('pays')->orderBy('name')->get(['id', 'name'])
                : collect(),
        ];
    }
}
