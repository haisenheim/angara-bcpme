<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;

class InstructionController extends Controller
{
    public function pending()
    {
        $items = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise.agence', 'programmeSelections.programme'])
            ->orderByDesc('programmes_submitted_at')
            ->get();

        return view('ChefFiliere.instructions.pending', compact('items'));
    }

    public function inProgress()
    {
        $items = Dossier::query()
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['entreprise', 'programme', 'gestionnaire', 'analyste'])
            ->orderByDesc('created_at')
            ->get();

        return view('ChefFiliere.instructions.in_progress', compact('items'));
    }

    public function showDossier(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['entreprise.agence', 'programme', 'gestionnaire', 'analyste', 'agence'])
            ->firstOrFail();

        return view('ChefFiliere.instructions.dossier_show', compact('dossier'));
    }
}
