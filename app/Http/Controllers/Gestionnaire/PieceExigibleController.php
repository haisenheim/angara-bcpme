<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Models\Entreprise;
use App\Models\EntreprisePieceExigible;
use App\Models\Fichier;
use App\Models\PieceExigibleDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PieceExigibleController extends ExtendedController
{
    public function index(string $token)
    {
        $item = Entreprise::where('token', $token)->firstOrFail();
        $this->authorizeEntreprise($item);
        $checklist = $item->piecesExigiblesChecklist();

        return view('Gestionnaire.Companies.pieces_exigibles', compact('item', 'checklist'));
    }

    public function store(Request $request, string $token, PieceExigibleDefinition $definition)
    {
        $item = Entreprise::where('token', $token)->firstOrFail();
        $this->authorizeEntreprise($item);

        $data = $request->validate([
            'fichier' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'notes' => 'nullable|string|max:5000',
        ]);

        $fileToken = sha1('piece-'.$item->id.'-'.$definition->id.'-'.microtime(true));
        $uri = $this->entityDocumentCreate($request->file('fichier'), 'pieces_exigibles', $fileToken);

        $fichier = Fichier::create([
            'name' => $uri,
            'entreprise_id' => $item->id,
            'type_id' => 0,
            'token' => $fileToken,
        ]);

        EntreprisePieceExigible::updateOrCreate(
            [
                'entreprise_id' => $item->id,
                'piece_exigible_definition_id' => $definition->id,
            ],
            [
                'fichier_id' => $fichier->id,
                'provided_at' => now(),
                'provided_by_user_id' => auth()->id(),
                'notes' => $data['notes'] ?? null,
            ]
        );

        Session::flash('success', 'Piece exigible televersee.');

        return redirect()->route('gestionnaire.entreprises.pieces-exigibles.index', $token);
    }

    private function authorizeEntreprise(Entreprise $entreprise): void
    {
        $userId = auth()->id();
        if ((int) $entreprise->gestionnaire_id !== (int) $userId && (int) $entreprise->user_id !== (int) $userId) {
            abort(403);
        }
    }
}
