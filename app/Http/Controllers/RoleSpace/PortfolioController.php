<?php

namespace App\Http\Controllers\RoleSpace;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;

class PortfolioController extends Controller
{
    use ResolvesRoleSpace;

    public function entreprisesIndex()
    {
        $space = $this->resolveSpace();
        $entreprises = Entreprise::query()
            ->with(['forme', 'user'])
            ->withCount('dossiers')
            ->orderByDesc('id')
            ->paginate(25);

        return view('RoleSpace.entreprises.index', compact('space', 'entreprises'));
    }

    public function entrepriseShow(string $token)
    {
        $space = $this->resolveSpace();
        $entreprise = Entreprise::query()
            ->with([
                'user',
                'agence',
                'dossiers.programme',
                'dossiers.analyste',
                'dossiers.gestionnaire',
                'dossierEntreeRelation',
                'dossierAnalyseCritique',
            ])
            ->where('token', $token)
            ->firstOrFail();

        return view('RoleSpace.entreprises.show', compact('space', 'entreprise'));
    }

    public function entreprisePieces(string $token)
    {
        $space = $this->resolveSpace();
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $checklist = $entreprise->piecesExigiblesChecklist();

        return view('RoleSpace.entreprises.pieces', compact('space', 'entreprise', 'checklist'));
    }

    public function dossiersIndex()
    {
        $space = $this->resolveSpace();
        $dossiers = Dossier::query()
            ->with(['entreprise', 'programme', 'analyste', 'gestionnaire'])
            ->orderByDesc('id')
            ->paginate(25);

        return view('RoleSpace.dossiers.index', compact('space', 'dossiers'));
    }

    public function dossierShow(string $token)
    {
        $space = $this->resolveSpace();
        $dossier = Dossier::query()
            ->with([
                'entreprise',
                'programme',
                'analyste',
                'gestionnaire',
                'agence',
                'indicateurs',
                'reponses',
            ])
            ->where('token', $token)
            ->firstOrFail();

        return view('RoleSpace.dossiers.show', compact('space', 'dossier'));
    }
}
