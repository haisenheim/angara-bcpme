<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PieceExigibleDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PieceExigibleDefinitionController extends Controller
{
    public function index()
    {
        $items = PieceExigibleDefinition::orderBy('sort_order')->orderBy('id')->get();

        return view('Admin.PieceExigibleDefinitions.index', [
            'items' => $items,
            'editItem' => null,
        ]);
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
        $items = PieceExigibleDefinition::orderBy('sort_order')->orderBy('id')->get();

        return view('Admin.PieceExigibleDefinitions.index', [
            'items' => $items,
            'editItem' => $pieces_exigible,
        ]);
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
