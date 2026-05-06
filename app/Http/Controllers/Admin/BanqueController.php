<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BanqueController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'microfinance' => (string) $request->query('microfinance', ''),
        ];

        $items = Banque::query()
            ->when($filters['q'] !== '', function (Builder $q) use ($filters) {
                $q->where(function (Builder $sub) use ($filters) {
                    $sub->where('name', 'like', '%'.$filters['q'].'%')
                        ->orWhere('siege', 'like', '%'.$filters['q'].'%')
                        ->orWhere('address', 'like', '%'.$filters['q'].'%');
                });
            })
            ->when($filters['microfinance'] !== '', fn (Builder $q) => $q->where('microfinance', (int) $filters['microfinance']))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Banque::count(),
            'banques' => Banque::where('microfinance', 0)->count(),
            'microfinances' => Banque::where('microfinance', 1)->count(),
        ];

        return view('Admin/Banques/index', compact('items', 'filters', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        Banque::create($data);
        Session::flash('success', 'Établissement créé avec succès.');

        return redirect()->route('admin.banques.index');
    }

    public function show($id)
    {
        $item = Banque::findOrFail($id);

        return view('Admin/Banques/show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $banque = Banque::findOrFail($id);
        $data = $this->validateData($request);
        $banque->update($data);
        Session::flash('success', 'Établissement mis à jour avec succès.');

        return redirect()->route('admin.banques.show', $banque->id);
    }

    public function destroy($id)
    {
        $banque = Banque::findOrFail($id);
        $banque->delete();
        Session::flash('success', 'Établissement supprimé.');

        return redirect()->route('admin.banques.index');
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'siege' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'microfinance' => ['nullable', 'boolean'],
        ]);

        $validated['microfinance'] = $request->boolean('microfinance', false) ? 1 : 0;

        return $validated;
    }
}
