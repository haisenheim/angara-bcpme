<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\FichierType;
use App\Services\Inertia\DossierInstructionPagePresenter;
use App\Services\Inertia\DossierWorkspacePresenter;
use App\Support\Inertia\DataTable;
use Illuminate\Http\Request;

class InstructionController extends Controller
{
    use StoresDossierPieces;

    public function pending(Request $request)
    {
        $items = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise.agence', 'programmeSelections.programme'])
            ->orderByDesc('programmes_submitted_at')
            ->paginate(25)
            ->withQueryString();

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'client', 'label' => 'Client'],
                ['key' => 'agence', 'label' => 'Agence'],
                ['key' => 'soumis', 'label' => 'Soumis le'],
                ['key' => 'programmes', 'label' => 'Programmes'],
            ])
            ->row(fn (DossierEntreeRelation $eer) => [
                'href' => $eer->entreprise?->token ? route('chef-filiere.clients.show', $eer->entreprise->token) : null,
                'cells' => [
                    'client' => $eer->entreprise?->name ?? '—',
                    'agence' => $eer->entreprise?->agence?->name,
                    'soumis' => $eer->programmes_submitted_at ? \Carbon\Carbon::parse($eer->programmes_submitted_at)->format('d/m/Y H:i') : null,
                    'programmes' => $eer->programmeSelections->pluck('programme.name')->filter()->implode(', ') ?: null,
                ],
            ])
            ->empty('Aucun dossier en attente de validation.');

        return \Inertia\Inertia::render('Dossiers/Index', [
            'title' => 'En attente de validation',
            'subtitle' => 'Dossiers soumis après structuration — le chef d\'agence valide avant instruction.',
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('chef-filiere.dashboard')],
                ['label' => 'Instruction — en attente'],
            ],
        ]);
    }

    public function inProgress(Request $request)
    {
        $items = Dossier::query()
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme', 'gestionnaire', 'analyste', 'indicateurs', 'reponses'])
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        $table = DataTable::make($items, $request)
            ->columns([
                ['key' => 'client', 'label' => 'Client'],
                ['key' => 'programmes', 'label' => 'Programme(s)'],
                ['key' => 'gestionnaire', 'label' => 'Gestionnaire'],
                ['key' => 'analyste', 'label' => 'Analyste'],
                ['key' => 'statut', 'label' => 'Statut'],
            ])
            ->row(fn (Dossier $d) => [
                'href' => route('chef-filiere.instructions.dossier.show', $d->token),
                'cells' => [
                    'client' => $d->entreprise?->name ?? '—',
                    'programmes' => $d->programmesLabel() ?: null,
                    'gestionnaire' => $d->gestionnaire?->name,
                    'analyste' => $d->analyste?->name,
                    'statut' => ['badge' => $d->status['name'] ?? '—', 'variant' => ($d->status['status'] ?? false) ? 'info' : 'neutral'],
                ],
            ])
            ->empty('Aucun dossier d\'instruction pour cette agence.');

        return \Inertia\Inertia::render('Dossiers/Index', [
            'title' => 'En cours d\'instruction',
            'subtitle' => 'Suivi en lecture seule des dossiers instruits par les équipes (gestionnaire, analyste) pour votre agence.',
            'table' => $table->toArray(),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('chef-filiere.dashboard')],
                ['label' => 'Instruction — en cours'],
            ],
        ]);
    }

    public function showDossier(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with(array_merge(DossierInstructionPagePresenter::RELATIONS, ['programme', 'entreprise.agence', 'indicateurs', 'reponses']))
            ->firstOrFail();

        $props = app(DossierWorkspacePresenter::class)->fiche($dossier, ['route' => 'chef-filiere', 'label' => 'Chef de filière', 'title' => 'Chef de filière'], auth()->user(), true, [
            'store' => 'chef-filiere.instructions.dossier.pieces.store',
            'update' => 'chef-filiere.instructions.dossier.pieces.update',
            'destroy' => 'chef-filiere.instructions.dossier.pieces.destroy',
            'upload' => true,
            'manageOwn' => true,
        ], 'chef-filiere.instructions.in-progress', 'Instruction en cours', 'chef-filiere.clients.show', false);
        $props['dossier']['suivi'] = $dossier->status['name'] ?? null;

        return \Inertia\Inertia::render('Dossiers/Show', $props);
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'chef-filiere.instructions.dossier.show', $dossier->token);
    }

    public function destroyDossierPiece(string $token, int $piece)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceDestroy($dossier, $fichier, 'chef-filiere.instructions.dossier.show', $dossier->token);
    }

    public function updateDossierPiece(Request $request, string $token, int $piece)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceUpdate($request, $dossier, $fichier, 'chef-filiere.instructions.dossier.show', $dossier->token);
    }
}
