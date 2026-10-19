<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFichierTypeRequest;
use App\Http\Requests\Admin\UpdateFichierTypeRequest;
use App\Models\Fichier;
use App\Models\FichierType;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class FichierTypeController extends Controller
{
    public function index(Request $request)
    {
        return $this->renderPage($request);
    }

    /**
     * Liste paginée côté serveur (mêmes filtres que l'ancien endpoint AJAX `fetchPaginated`, conservé)
     * ; la route `edit` ouvre directement le dialogue de modification.
     */
    private function renderPage(Request $request, ?FichierType $editItem = null)
    {
        $search = trim((string) $request->query('q', ''));
        $active = (string) $request->query('active', '');

        $items = FichierType::query()
            ->when($active !== '', fn ($q) => $q->where('active', (int) $active === 1))
            ->when($search !== '', fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->orderBy('name')->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Nom'],
                ['key' => 'active', 'label' => 'Statut', 'width' => '140px'],
            ])
            ->row(fn (FichierType $t) => [
                'cells' => [
                    'name' => $t->name ?: '—',
                    'active' => K::activeBadge((bool) ($t->active ?? true)),
                ],
                'actions' => [
                    $this->editAction($t),
                    K::toggle((bool) ($t->active ?? true), route('admin.fichiers-types.enable', $t), route('admin.fichiers-types.disable', $t), 'Désactiver'),
                ],
            ])
            ->search('Rechercher un type…')
            ->select('active', 'Statut', ['1' => 'Actifs', '0' => 'Inactifs'], 'Tous')
            ->empty('Aucun type de fichier.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Répertoire des pièces et documents',
            'subtitle' => 'Gestion des types de fichiers (table fichiers_types).',
            'countLabel' => 'type(s)',
            'table' => $table->toArray(),
            'actions' => [
                K::dialog('create', 'Nouveau type', 'Nouveau type', [
                    K::field('name', 'Nom', 'text', ['required' => true, 'value' => (string) old('name', '')]),
                ], route('admin.fichiers-types.store'), 'post', ['submitLabel' => 'Ajouter']),
            ],
            'autoAction' => $editItem ? $this->editAction($editItem) : null,
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Répertoire des pièces et documents'],
            ],
        ]);
    }

    private function editAction(FichierType $t): array
    {
        return K::dialog('edit-'.$t->id, 'Modifier', 'Modifier un type', [
            K::field('name', 'Nom', 'text', ['required' => true, 'value' => (string) old('name', $t->name ?? '')]),
        ], route('admin.fichiers-types.update', $t), 'put', ['submitLabel' => 'Mettre à jour']);
    }

    public function create()
    {
        return redirect()->route('admin.fichiers-types.index');
    }

    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim((string) $request->input('search.value', ''));

        $query = FichierType::query();

        $recordsTotal = FichierType::query()->count();

        $activeFilter = $request->input('active_filter');
        if ($activeFilter !== null && $activeFilter !== '') {
            $query->where('active', (int) $activeFilter === 1);
        }

        if ($search !== '') {
            $query->where('name', 'like', "%{$search}%");
        }

        $recordsFiltered = $query->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $columnMap = ['name', 'active'];
        $orderBy = $columnMap[$orderColumnIndex] ?? 'name';
        $query->orderBy($orderBy, $orderDir)->orderBy('id', 'asc');

        $items = $query->skip($start)->take($length)->get();
        $data = $items->map(fn (FichierType $t) => [
            'id' => $t->id,
            'name' => $t->name,
            'active' => (bool) ($t->active ?? true),
        ])->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function store(StoreFichierTypeRequest $request)
    {
        FichierType::query()->create([
            'name' => $request->validated('name'),
            'active' => true,
        ]);

        Session::flash('success', 'Type de fichier ajoute.');

        return redirect()->route('admin.fichiers-types.index');
    }

    public function show(FichierType $fichiers_type)
    {
        return redirect()->route('admin.fichiers-types.edit', $fichiers_type);
    }

    public function edit(Request $request, FichierType $fichiers_type)
    {
        return $this->renderPage($request, $fichiers_type);
    }

    public function update(UpdateFichierTypeRequest $request, FichierType $fichiers_type)
    {
        $fichiers_type->update([
            'name' => $request->validated('name'),
        ]);

        Session::flash('success', 'Type de fichier mis a jour.');

        return redirect()->route('admin.fichiers-types.index');
    }

    public function enable(FichierType $fichiers_type)
    {
        $fichiers_type->update(['active' => true]);
        Session::flash('success', 'Type active.');

        return redirect()->route('admin.fichiers-types.index');
    }

    public function disable(FichierType $fichiers_type)
    {
        $fichiers_type->update(['active' => false]);
        Session::flash('success', 'Type desactive.');

        return redirect()->route('admin.fichiers-types.index');
    }

    public function destroy(FichierType $fichiers_type)
    {
        $hasFiles = Fichier::query()->where('type_id', $fichiers_type->id)->exists();
        if ($hasFiles) {
            return redirect()
                ->route('admin.fichiers-types.index')
                ->with('error', 'Suppression impossible : ce type est deja utilise par des fichiers.');
        }

        $fichiers_type->delete();

        Session::flash('success', 'Type de fichier supprime.');

        return redirect()->route('admin.fichiers-types.index');
    }
}

