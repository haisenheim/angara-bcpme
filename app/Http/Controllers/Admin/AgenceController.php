<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Representation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class AgenceController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'representation_id' => (string) $request->query('representation_id', ''),
            'active' => (string) $request->query('active', ''),
        ];

        $items = Agence::query()
            ->with('representation')
            ->when($filters['q'] !== '', fn (Builder $q) => $q->where('name', 'like', '%'.$filters['q'].'%'))
            ->when($filters['representation_id'] !== '', fn (Builder $q) => $q->where('representation_id', (int) $filters['representation_id']))
            ->when($filters['active'] !== '', fn (Builder $q) => $q->where('active', (int) $filters['active']))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $representations = Representation::orderBy('name')->get();

        $stats = [
            'total' => Agence::count(),
            'active' => Agence::where('active', 1)->count(),
            'locked' => Agence::where('active', 0)->count(),
        ];

        $repOptions = $representations->pluck('name', 'id');

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Agence'],
                ['key' => 'representation', 'label' => 'Direction régionale'],
                ['key' => 'status', 'label' => 'Statut'],
            ])
            ->row(fn (Agence $item) => [
                'href' => route('admin.agences.show', $item->id),
                'cells' => [
                    'name' => $item->name,
                    'representation' => $item->representation?->name,
                    'status' => K::statusBadge($item->status),
                ],
                'actions' => [
                    K::link('show', 'Afficher', route('admin.agences.show', $item->id)),
                    K::toggle((bool) $item->active, route('admin.agences.enable', $item->id), route('admin.agences.disable', $item->id)),
                    K::destroy(route('admin.agences.destroy', $item->id), 'Supprimer définitivement cette agence ?'),
                ],
            ])
            ->search('Rechercher une agence...')
            ->select('representation_id', 'Direction régionale', $repOptions, 'Toutes les directions')
            ->select('active', 'Statut', ['1' => 'Actives', '0' => 'Verrouillées'], 'Tous les statuts')
            ->empty('Aucune agence');

        return Inertia::render('Admin/Crud', [
            'title' => 'Agences',
            'subtitle' => 'Liste des agences rattachées aux directions régionales',
            'countLabel' => 'agence(s)',
            'stats' => [
                K::stat('Total', $stats['total']),
                K::stat('Actives', $stats['active'], 'success'),
                K::stat('Verrouillées', $stats['locked'], 'danger'),
            ],
            'table' => $table->toArray(),
            'actions' => [
                K::dialog('create', 'Ajouter une agence', 'Nouvelle agence', $this->formFields($repOptions), route('admin.agences.store')),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Réseau'],
                ['label' => 'Agences'],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Agence::create($data);
        Session::flash('success', 'Agence créée avec succès.');

        return redirect()->route('admin.agences.index');
    }

    public function show(Agence $agence)
    {
        $agence->load('representation');
        $representations = Representation::orderBy('name')->get();

        $item = $agence;

        return Inertia::render('Admin/Fiche', [
            'title' => $item->name,
            'subtitle' => "Détails de l'agence",
            'badge' => K::statusBadge($item->status),
            'sections' => [[
                'title' => 'Agence',
                'items' => [
                    ['label' => 'Nom', 'value' => $item->name],
                    ['label' => 'Direction régionale', 'value' => $item->representation?->name ?? '-'],
                    ['label' => 'Statut', 'badge' => K::statusBadge($item->status)],
                ],
            ]],
            'actions' => [
                K::dialog('edit', 'Modifier', "Modifier l'agence", $this->formFields($representations->pluck('name', 'id'), $item), route('admin.agences.update', $item->id), 'put'),
                K::toggle((bool) $item->active, route('admin.agences.enable', $item->id), route('admin.agences.disable', $item->id)),
            ],
            'dangerActions' => [
                K::destroy(route('admin.agences.destroy', $item->id), 'Supprimer définitivement cette agence ?', 'Supprimer cette agence'),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Agences', 'href' => route('admin.agences.index')],
                ['label' => $item->name],
            ],
        ]);
    }

    public function update(Request $request, Agence $agence)
    {
        $data = $this->validateData($request, $agence->id);
        $agence->update($data);
        Session::flash('success', 'Agence mise à jour avec succès.');

        return redirect()->route('admin.agences.show', $agence->id);
    }

    public function destroy(Agence $agence)
    {
        $agence->delete();
        Session::flash('success', 'Agence supprimée.');

        return redirect()->route('admin.agences.index');
    }

    public function enable(Agence $agence)
    {
        $agence->update(['active' => 1]);
        Session::flash('success', 'Agence activée.');

        return back();
    }

    public function disable(Agence $agence)
    {
        $agence->update(['active' => 0]);
        Session::flash('success', 'Agence verrouillée.');

        return back();
    }

    private function formFields($repOptions, ?Agence $item = null): array
    {
        return [
            K::field('name', $item ? 'Nom' : "Nom de l'agence", 'text', ['required' => true, 'value' => $item?->name ?? '']),
            K::field('representation_id', 'Direction régionale', 'select', ['required' => true,
                'value' => $item ? (string) $item->representation_id : '', 'options' => K::options($repOptions)]),
            K::field('active', 'Agence active', 'checkbox', ['hint' => 'Agence active', 'value' => $item ? ((int) (bool) $item->active) : 1]),
        ];
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:150'],
            'representation_id' => ['required', 'integer', 'exists:representations,id'],
            'active' => ['nullable', 'boolean'],
        ];

        $validated = $request->validate($rules);
        $validated['active'] = $request->boolean('active', $ignoreId === null ? true : (bool) $request->input('active'));

        return $validated;
    }
}
