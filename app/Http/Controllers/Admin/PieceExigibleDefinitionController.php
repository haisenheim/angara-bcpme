<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PieceExigibleDefinition;
use App\Services\Inertia\Admin\CrudKit as K;
use App\Support\Inertia\DataTable;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;

class PieceExigibleDefinitionController extends Controller
{
    public function index()
    {
        return $this->renderPage();
    }

    public function create()
    {
        return redirect()->route('admin.pieces-exigibles.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        PieceExigibleDefinition::create([
            'label' => $data['label'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'active' => $request->boolean('active', true),
        ]);

       // Session::flash('success', 'Piece exigible ajoutee.');

        return redirect()->route('admin.pieces-exigibles.index');
    }

    public function show(PieceExigibleDefinition $pieces_exigible)
    {
        return redirect()->route('admin.pieces-exigibles.edit', $pieces_exigible);
    }

    public function edit(PieceExigibleDefinition $pieces_exigible)
    {
        return $this->renderPage($pieces_exigible);
    }

    private function renderPage(?PieceExigibleDefinition $editItem = null)
    {
        $items = PieceExigibleDefinition::orderBy('sort_order')->orderBy('id')->get();

        $table = DataTable::make(K::singlePage($items))
            ->columns([
                ['key' => 'order', 'label' => 'Ordre', 'width' => '80px'],
                ['key' => 'label', 'label' => 'Libellé'],
                ['key' => 'active', 'label' => 'État'],
            ])
            ->row(fn (PieceExigibleDefinition $item) => [
                'cells' => [
                    'order' => $item->sort_order,
                    'label' => ['title' => $item->label, 'subtitle' => $item->description],
                    'active' => K::activeBadge((bool) $item->active, 'Active', 'Inactive'),
                ],
                'actions' => [$this->formAction($item)],
            ])
            ->empty('Aucune pièce paramétrée.');

        return Inertia::render('Admin/Crud', [
            'title' => 'Pièces exigibles',
            'subtitle' => "Paramétrage de la checklist documentaire du dossier d'entrée en relation.",
            'countLabel' => 'pièce(s)',
            'table' => $table->toArray(),
            'actions' => [$this->formAction()],
            'autoAction' => $editItem ? $this->formAction($editItem) : null,
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => route('admin.dashboard')],
                ['label' => 'Pièces exigibles'],
            ],
        ]);
    }

    private function formAction(?PieceExigibleDefinition $item = null): array
    {
        $fields = [
            K::field('label', 'Libellé', 'text', ['required' => true, 'value' => (string) old('label', $item->label ?? '')]),
            K::field('description', 'Description', 'textarea', ['value' => (string) old('description', $item->description ?? '')]),
            K::field('sort_order', 'Ordre', 'number', ['value' => (string) old('sort_order', $item->sort_order ?? 0)]),
            K::field('active', 'État', 'checkbox', ['hint' => 'Active', 'value' => (int) (bool) old('active', $item?->active ?? true)]),
        ];

        return $item
            ? K::dialog('edit-'.$item->id, 'Modifier', 'Modifier une pièce', $fields, route('admin.pieces-exigibles.update', $item), 'put', ['submitLabel' => 'Mettre à jour'])
            : K::dialog('create', 'Nouvelle pièce exigible', 'Nouvelle pièce exigible', $fields, route('admin.pieces-exigibles.store'), 'post', ['submitLabel' => 'Ajouter']);
    }

    public function update(Request $request, PieceExigibleDefinition $pieces_exigible)
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
        ]);

        $pieces_exigible->update([
            'label' => $data['label'],
            'description' => $data['description'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
            'active' => $request->boolean('active'),
        ]);

        Session::flash('success', 'Piece exigible mise a jour.');

        return redirect()->route('admin.pieces-exigibles.index');
    }
}
