<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\ExtendedController;
use App\Models\EntreprisePieceExigible;
use App\Models\Fichier;
use App\Models\PieceExigibleDefinition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PieceExigibleController extends ExtendedController
{
    use AuthorizesAgenceEntreprise;

    public function index(string $token)
    {
        $item = $this->entrepriseForAgence($token);
        $checklist = app(\App\Services\Inertia\EntrepriseSheetPresenter::class)->checklist($item->piecesExigiblesChecklist());
        $back = route('chef-filiere.qualifications.show', $token);

        return \Inertia\Inertia::render('Entreprises/Pieces', [
            'entreprise' => ['name' => $item->name, 'token' => $item->token, 'href' => $back],
            'subtitle' => $item->name.' — documents de l\'entrée en relation.',
            'backLabel' => 'Retour à la structuration',
            'checklist' => array_map(fn ($row) => $row + [
                'uploadUrl' => route('chef-filiere.entreprises.pieces-exigibles.store', [$token, $row['id']]),
            ], $checklist),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('chef-filiere.dashboard')],
                ['label' => 'Structuration', 'href' => $back],
                ['label' => 'Pièces exigibles'],
            ],
        ]);
    }

    public function store(Request $request, string $token, PieceExigibleDefinition $definition)
    {
        $item = $this->entrepriseForAgence($token);

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

        Session::flash('success', 'Pièce exigible téléversée.');

        return redirect()->route('chef-filiere.entreprises.pieces-exigibles.index', $token);
    }
}
