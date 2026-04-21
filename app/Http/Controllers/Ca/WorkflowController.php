<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierAnalyseCritique;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WorkflowController extends Controller
{
    public function __construct(private readonly AnalyseCritiqueService $analyseCritiqueService) {}

    public function prospectIndex()
    {
        $items = Entreprise::query()
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id)
            ->with(['agence', 'juridiqueAvisUser', 'conformiteAvisUser'])
            ->orderByDesc('prospect_submitted_at')
            ->get();

        return view('Ca.Workflow.prospects_index', compact('items'));
    }

    public function prospectShow(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id)
            ->with([
                'agence',
                'region',
                'departement',
                'arrondissement',
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'promuClientUser',
                'prospectRejectedUser',
            ])
            ->firstOrFail();

        return view('Ca.Workflow.prospect_show', compact('item'));
    }

    public function prospectApprove(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('prospect', true)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        if (! $item->juridique_avis_at || ! $item->conformite_avis_at) {
            Session::flash('info', 'Les avis juridique et conformite doivent etre rendus avant validation du chef d\'agence.');

            return redirect()->route('ca.workflow.prospects.show', $token);
        }

        if ($item->prospect_rejected_at) {
            Session::flash('info', 'Ce prospect a deja ete refuse.');

            return redirect()->route('ca.workflow.prospects.show', $token);
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

        return redirect()->route('ca.workflow.prospects.index');
    }

    public function prospectReject(Request $request, string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('prospect', true)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        if (! $item->juridique_avis_at || ! $item->conformite_avis_at) {
            Session::flash('info', 'Les avis juridique et conformite doivent etre rendus avant une decision du chef d\'agence.');

            return redirect()->route('ca.workflow.prospects.show', $token);
        }

        if ($item->prospect_rejected_at) {
            Session::flash('info', 'Ce prospect a deja ete refuse.');

            return redirect()->route('ca.workflow.prospects.show', $token);
        }

        $data = $request->validate([
            'reject_motif' => 'nullable|string|max:5000',
        ]);

        $item->prospect = false;
        $item->prospect_rejected_at = now();
        $item->prospect_rejected_user_id = auth()->id();
        $item->save();

        $message = 'Prospect refuse par le chef d\'agence (non promu client).';
        if (! empty($data['reject_motif'])) {
            $message .= "\n\nMotif : ".$data['reject_motif'];
        }
        $this->analyseCritiqueService->syncProspectRejetChefAgence($item, $message);

        Session::flash('success', 'Le prospect a ete refuse. Les responsables ne peuvent plus modifier les avis.');

        return redirect()->route('ca.workflow.prospects.index');
    }

    public function instructionIndex()
    {
        $items = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise.agence', 'programmeSelections.programme'])
            ->orderByDesc('programmes_submitted_at')
            ->get();

        return view('Ca.Workflow.instructions_index', compact('items'));
    }

    public function instructionShow(string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with([
                'entreprise.agence',
                'entreprise.dossierAnalyseCritique.avis.emisPar',
                'programmeSelections.programme',
                'qualificationValidatedByAgenceUser',
            ])
            ->firstOrFail();

        return view('Ca.Workflow.instruction_show', compact('eer'));
    }

    public function approveInstruction(Request $request, string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise', 'programmeSelections.programme'])
            ->firstOrFail();

        if ($eer->qualification_validated_by_agence_at) {
            Session::flash('info', 'La qualification a déjà été validée.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        if ($eer->instruction_validated_at && $eer->statut === DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE) {
            Session::flash('info', 'La validation instruction a déjà été enregistrée.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        /** @deprecated Ancien flux : programmes sélectionnés à la qualification — création groupée de dossiers. */
        if ($eer->programmeSelections->isNotEmpty()) {
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
            $eer->qualification_validated_by_agence_at = $eer->qualification_validated_by_agence_at ?? now();
            $eer->qualification_validated_by_agence_user_id = $eer->qualification_validated_by_agence_user_id ?? auth()->id();
            $eer->save();

            $message = 'Dossier EER validé par le chef d\'agence. Dossiers d\'instruction créés pour : '.$eer->programmeSelections->pluck('programme.name')->filter()->implode(', ');
            $this->analyseCritiqueService->syncChefAgenceValidation($eer->entreprise, $eer, $message);

            foreach ($created as $dossier) {
                $this->analyseCritiqueService->syncInstructionDossier(
                    $dossier,
                    'Dossier d\'instruction créé suite à la validation du chef d\'agence.'
                );
            }

            Session::flash('success', 'Validation effectuée. Les dossiers d\'instruction ont été créés.');

            return $this->redirectAfterQualificationApprove($request, $eer, $token);
        }

        if ($eer->qualification_completed_at === null) {
            Session::flash('info', 'La qualification du chef de filière est incomplète.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        $eer->qualification_validated_by_agence_at = now();
        $eer->qualification_validated_by_agence_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE;
        $eer->save();

        $this->analyseCritiqueService->syncChefAgenceValidation(
            $eer->entreprise,
            $eer,
            'Qualification validée par le chef d\'agence. Le chef de filière inscrit le client aux programmes depuis la fiche client (un programme à la fois, dossier d\'instruction créé automatiquement).'
        );

        Session::flash('success', 'Qualification validée. Les inscriptions aux programmes se font sur la fiche client (chef de filière).');

        return $this->redirectAfterQualificationApprove($request, $eer, $token);
    }

    /**
     * Après validation depuis la fiche entreprise, renvoie vers celle-ci si le jeton correspond (pas d’open redirect).
     */
    private function redirectAfterQualificationApprove(Request $request, DossierEntreeRelation $eer, string $eerToken)
    {
        $returnToken = $request->input('return_entreprise_token');
        if (is_string($returnToken) && $returnToken !== '' && hash_equals((string) $eer->entreprise->token, $returnToken)) {
            return redirect()->route('ca.entreprises.show', $returnToken);
        }

        return redirect()->route('ca.workflow.instructions.show', $eerToken);
    }
}
