<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisme;
use App\Models\Torganisme;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
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

        return view('Admin/Organismes/index', array_merge($this->referenceData(), compact('items', 'filters')));
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

        return view('Admin/Organismes/show', array_merge($this->referenceData(), ['item' => $organisme]));
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
