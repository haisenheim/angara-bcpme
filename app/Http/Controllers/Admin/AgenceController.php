<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Representation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        return view('Admin/Agences/index', compact('items', 'representations', 'filters', 'stats'));
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

        return view('Admin/Agences/show', ['item' => $agence, 'representations' => $representations]);
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
