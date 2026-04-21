<?php

namespace App\Http\Controllers\Juridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;

class DossierController extends Controller
{
    public function index()
    {
        $dossiers = Dossier::query()
            ->whereNotNull('juridique_instruction_submitted_at')
            ->with(['entreprise', 'programme', 'analyste', 'gestionnaire', 'agence', 'juridiqueInstructionSubmittedBy'])
            ->orderByDesc('juridique_instruction_submitted_at')
            ->paginate(25);

        return view('Juridique.Dossiers.index', compact('dossiers'));
    }

    public function show(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->whereNotNull('juridique_instruction_submitted_at')
            ->with([
                'entreprise',
                'programme',
                'analyste',
                'gestionnaire',
                'agence',
                'exploitationAvisCreditUser',
                'exploitationEngagementsDecisionUser',
                'juridiqueInstructionSubmittedBy',
                'exploitationInstructionSubmittedBy',
            ])
            ->firstOrFail();

        return view('Juridique.Dossiers.show', compact('dossier'));
    }
}
