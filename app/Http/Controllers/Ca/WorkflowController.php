<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierAnalyseCritique;
use App\Models\DossierEntreeRelation;
use App\Models\DossierEntreeRelationProgramme;
use App\Models\Entreprise;
use App\Services\AnalyseCritiqueService;
use App\Services\InstructionDelegationService;
use App\Services\InstructionDossierConsultationService;
use App\Services\StructurationClosureService;
use App\Services\WorkflowEmailNotificationService;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WorkflowController extends Controller
{
    public function __construct(
        private readonly AnalyseCritiqueService $analyseCritiqueService,
        private readonly InstructionDelegationService $delegationService,
        private readonly StructurationClosureService $structurationClosureService,
    ) {}

    /** Dossiers d’instruction : périmètre agence pour le chef d’agence ; tous les dossiers pour DG / DGA. */
    private function baseInstructionDossierQuery(): Builder
    {
        $user = auth()->user();
        $q = Dossier::on('central_app_mysql')->whereHas('instructionProgrammes');
        if (! $this->delegationService->isDirectionRole($user)) {
            $q->whereHas('entreprise', fn ($q2) => $q2->where('agence_id', $user->agence_id));
        }

        return $q;
    }

    public function prospectIndex()
    {
        $items = Entreprise::query()
            ->submittedProspect()
            ->with(['agence', 'juridiqueAvisUser', 'conformiteAvisUser'])
            ->orderByDesc('prospect_submitted_at')
            ->get();

        return view('Ca.Workflow.prospects_index', compact('items'));
    }

    public function prospectShow(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->submittedProspect()
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
            ->whereNotNull('prospect_submitted_at')
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

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForEntreprise($item);
        $gestionnaire = $item->gestionnaire_id
            ? User::query()->whereKey((int) $item->gestionnaire_id)->first()
            : ($item->user_id ? User::query()->whereKey((int) $item->user_id)->first() : null);
        if ($gestionnaire) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect validé — promu client',
                title: 'Décision chef d’agence',
                body: "Le prospect a été validé par le chef d’agence et promu au statut client.\n\nVous pouvez consulter la fiche client et poursuivre le parcours (structuration).",
                ctaLabel: 'Ouvrir la fiche',
                ctaUrl: route('gestionnaire.entreprises.show', $item->token),
                event: 'prospect_approved_by_ca'
            );
            $mailer->notifyUser($gestionnaire, auth()->user(), $payload, $ctx);
        }

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

        $item->prospect_rejected_at = now();
        $item->prospect_rejected_user_id = auth()->id();
        // Réouverture côté gestionnaire : le prospect redevient un brouillon modifiable, prêt à être resoumis.
        $item->prospect_submitted_at = null;
        $item->juridique_avis = null;
        $item->juridique_avis_at = null;
        $item->juridique_avis_user_id = null;
        $item->conformite_avis = null;
        $item->conformite_avis_at = null;
        $item->conformite_avis_user_id = null;
        $item->save();

        $message = 'Prospect refuse par le chef d\'agence (non promu client).';
        if (! empty($data['reject_motif'])) {
            $message .= "\n\nMotif : ".$data['reject_motif'];
        }
        $this->analyseCritiqueService->syncProspectRejetChefAgence($item, $message);

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForEntreprise($item);
        $gestionnaire = $item->gestionnaire_id
            ? User::query()->whereKey((int) $item->gestionnaire_id)->first()
            : ($item->user_id ? User::query()->whereKey((int) $item->user_id)->first() : null);
        if ($gestionnaire) {
            $payload = $mailer->buildPayload(
                subject: 'Prospect refusé',
                title: 'Décision chef d’agence',
                body: "Le prospect a été refusé par le chef d’agence.\n\nConsultez la fiche pour voir les détails et le motif (si renseigné).",
                ctaLabel: 'Ouvrir la fiche',
                ctaUrl: route('gestionnaire.entreprises.show', $item->token),
                event: 'prospect_rejected_by_ca'
            );
            $mailer->notifyUser($gestionnaire, auth()->user(), $payload, $ctx);
        }

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
                'qualificationRejectedByAgenceUser',
            ])
            ->firstOrFail();

        $instructionDossiersChefFiliere = Dossier::on('central_app_mysql')
            ->where('entreprise_id', $eer->entreprise_id)
            ->whereHas('instructionProgrammes')
            ->with([
                'instructionProgrammes.programme',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('Ca.Workflow.instruction_show', compact('eer', 'instructionDossiersChefFiliere'));
    }

    public function approveInstruction(Request $request, string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with(['entreprise', 'programmeSelections.programme'])
            ->firstOrFail();

        if ($eer->qualification_validated_by_agence_at) {
            Session::flash('info', 'La structuration a déjà été validée.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        if ($eer->instruction_validated_at && $eer->statut === DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE) {
            Session::flash('info', 'La validation instruction a déjà été enregistrée.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        /** @deprecated Ancien flux : programmes sélectionnés à la structuration — création groupée de dossiers. */
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
            $eer->qualification_rejected_by_agence_at = null;
            $eer->qualification_rejected_by_agence_user_id = null;
            $eer->qualification_reject_motif = null;
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
            Session::flash('info', 'La structuration du chef de filière est incomplète.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        $eer->qualification_validated_by_agence_at = now();
        $eer->qualification_validated_by_agence_user_id = auth()->id();
        $eer->statut = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE;
        $eer->qualification_rejected_by_agence_at = null;
        $eer->qualification_rejected_by_agence_user_id = null;
        $eer->qualification_reject_motif = null;
        $eer->save();

        $this->analyseCritiqueService->syncChefAgenceValidation(
            $eer->entreprise,
            $eer,
            'Structuration validée par le chef d\'agence. Le chef de filière compose ensuite le dossier d\'instruction (plusieurs programmes et budgets d\'appui) sur la fiche client et le soumet pour validation.'
        );

        Session::flash('success', 'Structuration validée. Le chef de filière peut constituer le dossier d\'instruction multi-programmes sur la fiche client.');

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

    /**
     * Refus de la structuration (EER) par le chef d’agence : le chef de filière peut corriger et resoumettre.
     */
    public function rejectQualification(Request $request, string $token)
    {
        $eer = DossierEntreeRelation::query()
            ->where('token', $token)
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', auth()->user()->agence_id))
            ->with('entreprise')
            ->firstOrFail();

        if ($eer->qualification_validated_by_agence_at) {
            Session::flash('info', 'La structuration est déjà validée.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        if ($eer->programmes_submitted_at === null) {
            Session::flash('info', 'Aucune structuration transmise par le chef de filière à refuser.');

            return redirect()->route('ca.workflow.instructions.show', $token);
        }

        $data = $request->validate([
            'reject_motif' => 'nullable|string|max:5000',
        ]);

        $eer->qualification_rejected_by_agence_at = now();
        $eer->qualification_rejected_by_agence_user_id = auth()->id();
        $eer->qualification_reject_motif = $data['reject_motif'] ?? null;
        $eer->programmes_submitted_at = null;
        $eer->programmes_submitted_by_user_id = null;
        $eer->statut = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_REJETEE;
        $eer->instruction_validation_status = DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_REJETEE;
        $eer->save();

        $message = 'Structuration refusée par le chef d\'agence. Le chef de filière peut la corriger et la resoumettre.';
        if (! empty($data['reject_motif'])) {
            $message .= "\n\nMotif : ".$data['reject_motif'];
        }
        $this->analyseCritiqueService->syncChefAgenceRejetStructuration($eer->entreprise, $eer, $message);

        Session::flash('success', 'Structuration refusée. Le chef de filière a été informé et peut modifier la structuration puis la resoumettre.');

        return $this->redirectAfterQualificationApprove($request, $eer, $token);
    }

    /**
     * Dossiers d’instruction (multi-programmes) soumis par le chef de filière, en attente de validation (un ou plusieurs par client).
     */
    public function instructionDossierBundleIndex()
    {
        $items = $this->baseInstructionDossierQuery()
            ->whereNotNull('chef_filiere_submitted_to_agence_at')
            ->whereNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at')
            ->with(['entreprise.agence', 'instructionProgrammes.programme', 'chefFiliereSubmittedToAgenceBy'])
            ->orderByDesc('chef_filiere_submitted_to_agence_at')
            ->get();

        return view('Ca.Workflow.instruction_dossiers_index', compact('items'));
    }

    /**
     * @param  string  $token  Jeton du dossier d’instruction (pas celui de l’EER).
     */
    public function instructionDossierBundleShow(string $token)
    {
        $dossier = $this->baseInstructionDossierQuery()
            ->where('token', $token)
            ->with([
                'entreprise.agence',
                'instructionProgrammes.programme',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();

        $instructionConsultation = app(InstructionDossierConsultationService::class)->build($dossier);
        $canApproveRejectInstructionTransmission = $this->structurationClosureService->canChefAgenceDecide(auth()->user(), $dossier);
        $closureStatutLabel = $this->structurationClosureService->closureStatutLabel($dossier);

        return view('Ca.Workflow.instruction_dossier_bundle_show', compact(
            'dossier',
            'instructionConsultation',
            'canApproveRejectInstructionTransmission',
            'closureStatutLabel',
        ));
    }

    public function approveInstructionBundle(Request $request, string $token)
    {
        $request->validate([
            'closing_note' => 'nullable|string|max:5000',
        ]);

        $central = 'central_app_mysql';
        $dossier = $this->baseInstructionDossierQuery()
            ->where('token', $token)
            ->with(['entreprise', 'instructionProgrammes.programme'])
            ->firstOrFail();

        if (! $this->structurationClosureService->canChefAgenceDecide(auth()->user(), $dossier)) {
            Session::flash('info', 'Votre profil n’est pas habilité à valider cette structuration : seul le chef d’agence de l’agence du dossier peut décider.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $eer = DossierEntreeRelation::query()->where('entreprise_id', $dossier->entreprise_id)->first();

        if ($dossier->chef_filiere_submitted_to_agence_at === null) {
            Session::flash('info', 'Ce dossier n’a pas été transmis par le chef de filière.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        if ($dossier->instruction_agence_rejected_at !== null) {
            Session::flash('info', 'Ce dossier a été rejeté. Le chef de filière peut soumettre une nouvelle proposition.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        if ($dossier->instruction_agence_validated_at !== null) {
            Session::flash('info', 'Ce dossier d’instruction a déjà été validé.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        if ($dossier->instructionProgrammes->isEmpty()) {
            Session::flash('info', 'Le dossier d’instruction est incomplet (aucun programme).');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $validatedNow = false;

        DB::connection($central)->transaction(function () use ($eer, $dossier, &$validatedNow, $central, $request) {
            $dLocked = Dossier::on($central)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if ($dLocked->instruction_agence_validated_at !== null || $dLocked->instruction_agence_rejected_at !== null) {
                return;
            }

            if ($eer) {
                DossierEntreeRelationProgramme::query()
                    ->where('dossier_entree_relation_id', $eer->id)
                    ->where('instruction_dossier_id', $dLocked->id)
                    ->update([
                        'statut' => DossierEntreeRelationProgramme::STATUT_VALIDE,
                        'validated_at' => now(),
                    ]);
            }

            $dLocked->instruction_agence_validated_at = now();
            $dLocked->instruction_agence_validated_by_user_id = auth()->id();
            $dLocked->instruction_agence_closing_note = $request->input('closing_note');
            $dLocked->save();
            $validatedNow = true;
        });

        if (! $validatedNow) {
            $dFresh = $dossier->fresh();
            if ($dFresh?->instruction_agence_rejected_at !== null) {
                Session::flash('info', 'Ce dossier a été rejeté entre-temps. Rechargez la page.');
            } else {
                Session::flash('info', 'Ce dossier d’instruction a déjà été traité.');
            }

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $dossier->refresh();
        $label = $dossier->loadMissing('instructionProgrammes.programme')->programmesLabel();

        if ($eer) {
            $this->analyseCritiqueService->syncChefAgenceValidation(
                $dossier->entreprise,
                $eer->fresh(),
                'Dossier d’instruction multi-programmes validé par le chef d’agence. Programmes : '.$label.'.'
            );
        }

        $this->analyseCritiqueService->syncInstructionDossier(
            $dossier,
            'Dossier d’instruction validé par le chef d’agence (programmes : '.$label.').'
        );

        Session::flash('success', 'Dossier d’instruction validé. Les programmes et budgets d’appui sont enregistrés.');

        return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
    }

    public function rejectInstructionBundle(Request $request, string $token)
    {
        $central = 'central_app_mysql';
        $data = $request->validate([
            'reject_motif' => 'nullable|string|max:5000',
            'closing_note' => 'nullable|string|max:5000',
        ]);

        $dossier = $this->baseInstructionDossierQuery()
            ->where('token', $token)
            ->with(['entreprise', 'instructionProgrammes.programme'])
            ->firstOrFail();

        if (! $this->structurationClosureService->canChefAgenceDecide(auth()->user(), $dossier)) {
            Session::flash('info', 'Votre profil n’est pas habilité à rejeter cette structuration : seul le chef d’agence de l’agence du dossier peut décider.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $eer = DossierEntreeRelation::query()->where('entreprise_id', $dossier->entreprise_id)->first();

        if (! $dossier->isInstructionPendingAgenceValidation()) {
            if ($dossier->instruction_agence_validated_at !== null) {
                Session::flash('info', 'Ce dossier d’instruction a déjà été validé.');
            } elseif ($dossier->instruction_agence_rejected_at !== null) {
                Session::flash('info', 'Ce dossier a déjà été rejeté.');
            } else {
                Session::flash('info', 'Aucune soumission en attente de validation pour ce dossier.');
            }

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        if ($dossier->instructionProgrammes->isEmpty()) {
            Session::flash('info', 'Le dossier d’instruction est incomplet.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $rejectedNow = false;

        DB::connection($central)->transaction(function () use ($eer, $dossier, $data, &$rejectedNow, $central) {
            $dLocked = Dossier::on($central)->whereKey($dossier->id)->lockForUpdate()->firstOrFail();
            if (! $dLocked->isInstructionPendingAgenceValidation()) {
                return;
            }

            if ($eer) {
                DossierEntreeRelationProgramme::query()
                    ->where('dossier_entree_relation_id', $eer->id)
                    ->where('instruction_dossier_id', $dLocked->id)
                    ->update([
                        'statut' => DossierEntreeRelationProgramme::STATUT_REJETE,
                        'validated_at' => null,
                    ]);
            }

            $dLocked->instruction_agence_rejected_at = now();
            $dLocked->instruction_agence_rejected_by_user_id = auth()->id();
            $dLocked->instruction_agence_reject_motif = $data['reject_motif'] ?? null;
            $dLocked->instruction_agence_closing_note = $data['closing_note'] ?? null;
            $dLocked->save();
            $rejectedNow = true;
        });

        if (! $rejectedNow) {
            Session::flash('info', 'La demande n’a pas pu être traitée (état modifié). Rechargez la page.');

            return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
        }

        $dossier->refresh();
        $label = $dossier->loadMissing('instructionProgrammes.programme')->programmesLabel();
        $message = 'Dossier d’instruction multi-programmes rejeté par le chef d’agence. Programmes concernés : '.$label.'.';
        if (! empty($data['reject_motif'])) {
            $message .= "\n\nMotif : ".$data['reject_motif'];
        }

        if ($eer) {
            $this->analyseCritiqueService->syncChefAgenceValidation($dossier->entreprise, $eer->fresh(), $message);
        }
        $this->analyseCritiqueService->syncInstructionDossier(
            $dossier,
            'Dossier d’instruction non validé par le chef d’agence (rejet). Programmes : '.$label.'.'
            .(! empty($data['reject_motif']) ? "\n\nMotif : ".$data['reject_motif'] : '')
        );

        Session::flash('success', 'Le dossier d’instruction a été rejeté. Le chef de filière peut le corriger et le soumettre à nouveau.');

        return redirect()->route('ca.workflow.instruction-dossiers.show', $token);
    }
}
