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
        $checklist = app(\App\Services\Inertia\EntrepriseSheetPresenter::class)->checklist($item->piecesExigiblesChecklist());
        $show = route('gestionnaire.entreprises.show', $token);

        return \Inertia\Inertia::render('Entreprises/Pieces', [
            'entreprise' => ['name' => $item->name, 'token' => $item->token, 'href' => $show],
            'title' => 'Checklist des pièces exigibles',
            'subtitle' => $item->name.' — documents du dossier d\'entrée en relation.',
            'checklist' => array_map(fn ($row) => $row + [
                'uploadUrl' => route('gestionnaire.entreprises.pieces-exigibles.store', [$token, $row['id']]),
            ], $checklist),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('gestionnaire.dashboard')],
                ['label' => \Illuminate\Support\Str::limit((string) $item->name, 40), 'href' => $show],
                ['label' => 'Pièces exigibles'],
            ],
        ]);
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
        if (! $entreprise->isManagedBy($userId)) {
            abort(403);
        }
    }
}
