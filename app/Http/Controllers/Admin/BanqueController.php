<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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

        $typeBadge = fn (Banque $b) => $b->microfinance
            ? ['badge' => 'Microfinance', 'variant' => 'info']
            : ['badge' => 'Banque', 'variant' => 'neutral'];

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Nom'],
                ['key' => 'siege', 'label' => 'Siège'],
                ['key' => 'type', 'label' => 'Type'],
            ])
            ->row(fn (Banque $item) => [
                'href' => route('admin.banques.show', $item->id),
                'cells' => [
                    'name' => $item->name,
                    'siege' => $item->siege ?: '-',
                    'type' => $typeBadge($item),
                ],
                'actions' => [
                    K::link('show', 'Afficher', route('admin.banques.show', $item->id)),
                    K::destroy(route('admin.banques.destroy', $item->id), 'Supprimer cet établissement ?'),
                ],
            ])
            ->search('Rechercher (nom, siège, adresse)...')
            ->select('microfinance', 'Type', ['0' => 'Banques', '1' => 'Microfinances'], 'Tous les types')
            ->empty('Aucun établissement');

        return Inertia::render('Admin/Crud', [
            'title' => 'Banques & microfinances',
            'subtitle' => 'Liste des établissements financiers partenaires',
            'countLabel' => 'établissement(s)',
            'stats' => [
                K::stat('Total', $stats['total']),
                K::stat('Banques', $stats['banques'], 'info'),
                K::stat('Microfinances', $stats['microfinances'], 'info'),
            ],
            'table' => $table->toArray(),
            'actions' => [
                K::dialog('create', 'Ajouter un établissement', 'Nouvel établissement', self::formFields(), route('admin.banques.store'), 'post', ['wide' => true]),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Paramètres'],
                ['label' => 'Banques'],
            ],
        ]);
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

        $badge = $item->microfinance ? ['badge' => 'Microfinance', 'variant' => 'info'] : ['badge' => 'Banque', 'variant' => 'neutral'];

        return Inertia::render('Admin/Fiche', [
            'title' => $item->name,
            'subtitle' => "Détails de l'établissement",
            'badge' => $badge,
            'sections' => [[
                'title' => 'Établissement',
                'items' => [
                    ['label' => 'Nom', 'value' => $item->name],
                    ['label' => 'Siège', 'value' => $item->siege ?: '-'],
                    ['label' => 'Adresse', 'value' => $item->address ?: '-', 'multiline' => true],
                    ['label' => 'Type', 'badge' => $badge],
                ],
            ]],
            'actions' => [
                K::dialog('edit', 'Modifier', "Modifier l'établissement", self::formFields($item), route('admin.banques.update', $item->id), 'put', ['wide' => true]),
            ],
            'dangerActions' => [
                K::destroy(route('admin.banques.destroy', $item->id), 'Supprimer cet établissement ?', 'Supprimer cet établissement'),
            ],
            'breadcrumb' => [
                ['label' => 'ANGARA', 'href' => route('admin.dashboard')],
                ['label' => 'Banques & microfinances', 'href' => route('admin.banques.index')],
                ['label' => $item->name],
            ],
        ]);
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

    private static function formFields(?Banque $item = null): array
    {
        return [
            K::field('name', $item ? 'Nom' : "Nom de l'établissement", 'text', ['required' => true, 'value' => $item?->name ?? '']),
            K::field('siege', 'Siège', 'text', ['value' => $item?->siege ?? '']),
            K::field('address', 'Adresse', 'textarea', ['value' => $item?->address ?? '']),
            K::field('microfinance', 'Microfinance', 'checkbox', ['hint' => 'Établissement de microfinance', 'value' => (int) (bool) ($item?->microfinance ?? 0)]),
        ];
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
