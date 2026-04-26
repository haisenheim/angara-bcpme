<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFichierTypeRequest;
use App\Http\Requests\Admin\UpdateFichierTypeRequest;
use App\Models\Fichier;
use App\Models\FichierType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FichierTypeController extends Controller
{
    public function index()
    {
        return view('Admin.FichierTypes.index', [
            'editItem' => null,
        ]);
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

    public function edit(FichierType $fichiers_type)
    {
        return view('Admin.FichierTypes.index', [
            'editItem' => $fichiers_type,
        ]);
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

