<?php

namespace App\Http\Controllers\ChefAgence;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierAnalyseCritique;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use Illuminate\Support\Facades\Session;

class ProspectController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService)
    {
    }

    public function index()
    {
        $items = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['agence', 'juridiqueAvisUser', 'conformiteAvisUser'])
            ->orderByDesc('prospect_submitted_at')
            ->get();

        return view('ChefAgence.prospects.index', compact('items'));
    }

    public function show(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('prospect', true)
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['agence', 'region', 'departement', 'arrondissement', 'juridiqueAvisUser', 'conformiteAvisUser'])
            ->firstOrFail();

        return view('ChefAgence.prospects.show', compact('item'));
    }

    public function approve(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('prospect', true)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        if (! $item->juridique_avis_at || ! $item->conformite_avis_at) {
            Session::flash('info', 'Les avis juridique et conformite doivent etre rendus avant validation du chef d\'agence.');

            return redirect()->route('chef-agence.prospects.show', $token);
        }

        $item->prospect = false;
        $item->promu_client_at = now();
        $item->promu_client_user_id = auth()->id();
        $item->save();

        $eer = DossierEntreeRelation::firstOrCreate(
            ['entreprise_id' => $item->id],
            [
                'token' => sha1('eer-'.$item->id.'-'.microtime(true)),
                'statut' => DossierEntreeRelation::STATUT_CLIENT_VALIDE,
                'submitted_at' => $item->prospect_submitted_at,
                'submitted_by_user_id' => $item->gestionnaire_id ?: $item->user_id,
            ]
        );
        $eer->statut = DossierEntreeRelation::STATUT_CLIENT_VALIDE;
        $eer->submitted_at = $eer->submitted_at ?: $item->prospect_submitted_at;
        $eer->submitted_by_user_id = $eer->submitted_by_user_id ?: ($item->gestionnaire_id ?: $item->user_id);
        $eer->save();

        DossierAnalyseCritique::firstOrCreate(
            ['entreprise_id' => $item->id],
            ['token' => sha1('dac-'.$item->id.'-'.microtime(true))]
        );

        $this->analyseCritiqueService->syncProspectWorkflow($item);
        $this->analyseCritiqueService->syncChefAgenceValidation(
            $item,
            $eer,
            'Prospect valide par le chef d\'agence et promu au statut client.'
        );

        Session::flash('success', 'Prospect valide. Le client peut maintenant etre qualifie par le chef de filiere.');

        return redirect()->route('chef-agence.prospects.index');
    }

    public function instructionIndex()
    {
        $items = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise.agence', 'programmeSelections.programme'])
            ->orderByDesc('programmes_submitted_at')
            ->get();

        return view('ChefAgence.instructions.index', compact('items'));
    }

    public function instructionShow(string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise.agence', 'entreprise.dossierAnalyseCritique.avis.emisPar', 'programmeSelections.programme'])
            ->firstOrFail();

        return view('ChefAgence.instructions.show', compact('eer'));
    }

    public function approveInstruction(string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise', 'programmeSelections.programme'])
            ->firstOrFail();

        if ($eer->programmeSelections->isEmpty()) {
            Session::flash('info', 'Aucun programme selectionne pour ce dossier.');

            return redirect()->route('chef-agence.instructions.show', $token);
        }

        $created = collect();
        foreach ($eer->programmeSelections as $selection) {
            $existingInstruction = $selection->instruction_dossier_id
                ? Dossier::find($selection->instruction_dossier_id)
                : null;

            $dossier = Dossier::updateOrCreate(
                [
                    'entreprise_id' => $eer->entreprise_id,
                    'programme_id' => $selection->programme_id,
                ],
                [
                    'token' => $existingInstruction?->token ?: sha1('instruction-'.$eer->entreprise_id.'-'.$selection->programme_id.'-'.microtime(true)),
                    'gestionnaire_id' => $eer->entreprise->gestionnaire_id ?: $eer->entreprise->user_id ?: auth()->id(),
                    'agence_id' => $eer->entreprise->agence_id,
                    'representation_id' => $eer->entreprise->representation_id,
                    'active' => 0,
                ]
            );

            $selection->instruction_dossier_id = $dossier->id;
            $selection->validated_at = now();
            $selection->statut = DossierEntreeRelationProgramme::STATUT_VALIDE;
            $selection->save();

            $created->push($dossier);
        }

        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE;
        $eer->instruction_validated_at = now();
        $eer->instruction_validated_by_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE;
        $eer->save();

        $message = 'Dossier EER valide par le chef d\'agence. Dossiers d\'instruction crees pour : '.$eer->programmeSelections->pluck('programme.name')->filter()->implode(', ');
        $this->analyseCritiqueService->syncChefAgenceValidation($eer->entreprise, $eer, $message);

        foreach ($created as $dossier) {
            $this->analyseCritiqueService->syncInstructionDossier(
                $dossier,
                'Dossier d\'instruction cree suite a la validation du chef d\'agence.'
            );
        }

        Session::flash('success', 'Validation effectuee. Les dossiers d\'instruction ont ete crees.');

        return redirect()->route('chef-agence.instructions.show', $token);
    }
}
