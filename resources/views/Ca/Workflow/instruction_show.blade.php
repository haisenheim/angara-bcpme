@extends('Layouts.ca')

@section('title', 'Validation structuration')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $eer->entreprise?->name ?? 'Dossier EER' }}</h1>
        <p class="text-muted mb-0">Validation de la structuration par le chef d’agence. Ensuite, le chef de filière constitue sur la fiche client le dossier d’instruction multi-programmes (budgets d’appui) et le soumet pour validation dans « Dossiers d’instruction (multi-programmes) ».</p>
    </div>
@endsection

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-7">
            @if($eer->programmeSelections->isNotEmpty())
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <strong>Programmes (ancien flux)</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Programme</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Dossier créé</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eer->programmeSelections as $selection)
                                <tr>
                                    <td>{{ $selection->programme?->name ?? '-' }}</td>
                                    <td>{{ $selection->type_appui }}</td>
                                    <td><span class="badge bg-info">{{ $selection->statut }}</span></td>
                                    <td>{{ $selection->instruction_dossier_id ? 'Oui' : 'Non' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="alert alert-light border mb-4">
                <strong>Flux actuel :</strong> aucun programme n’est joint à la structuration. Après validation, le chef de filière compose sur la fiche client un <strong>dossier d’instruction multi-programmes</strong> (budgets d’appui financier et non financier par programme), puis le soumet au chef d’agence pour validation dans « Dossiers d’instruction (multi-programmes) ».
            </div>
            @endif

            @if($eer->qualification_validated_by_agence_at)
                @php
                    $pendingBund = $instructionDossiersChefFiliere->filter(fn ($d) => $d->isInstructionPendingAgenceValidation())->count();
                @endphp
                <div class="card shadow-sm border-0 mb-4 border-start border-4 border-primary">
                    <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <strong>Dossiers d’instruction (chef de filière → agence)</strong>
                        @if($pendingBund > 0)
                            <span class="badge bg-warning text-dark">{{ $pendingBund }} en attente</span>
                        @elseif($instructionDossiersChefFiliere->isEmpty())
                            <span class="badge bg-secondary">Aucun dossier</span>
                        @else
                            <span class="badge bg-success">Aucune attente</span>
                        @endif
                    </div>
                    <div class="card-body">
                        @forelse($instructionDossiersChefFiliere as $dInst)
                            @php
                                $st = $dInst->isInstructionPendingAgenceValidation() ? 'pending' : ($dInst->isInstructionValidatedByAgence() ? 'ok' : ($dInst->isInstructionRejectedByAgence() ? 'rej' : 'other'));
                            @endphp
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex flex-wrap justify-content-between gap-2 mb-2">
                                    <strong class="small">{{ $dInst->programmesLabel() }}</strong>
                                    @if($st === 'pending')
                                        <span class="badge bg-warning text-dark">À valider</span>
                                    @elseif($st === 'ok')
                                        <span class="badge bg-success">Validé agence</span>
                                    @elseif($st === 'rej')
                                        <span class="badge bg-danger">Rejeté agence</span>
                                    @else
                                        <span class="badge bg-secondary">—</span>
                                    @endif
                                </div>
                                <p class="small text-muted mb-2">
                                    Transmis le {{ $dInst->chef_filiere_submitted_to_agence_at?->format('d/m/Y H:i') ?? '—' }}
                                    @if($dInst->chefFiliereSubmittedToAgenceBy) — {{ $dInst->chefFiliereSubmittedToAgenceBy->name }} @endif
                                </p>
                                <a href="{{ route('ca.workflow.instruction-dossiers.show', $dInst->token) }}" class="btn btn-sm btn-outline-primary">Ouvrir / traiter</a>
                                @if($dInst->isInstructionValidatedByAgence())
                                    <a href="{{ route('ca.dossiers.show', $dInst->token) }}" class="btn btn-sm btn-outline-secondary ms-1">Fiche dossier</a>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted small mb-0">
                                Aucun dossier d’instruction multi-programmes. Fiche client :
                                @if($eer->entreprise?->token)
                                    <a href="{{ route('ca.entreprises.show', $eer->entreprise->token) }}">{{ $eer->entreprise->name }}</a>.
                                @else
                                    —
                                @endif
                            </p>
                        @endforelse
                        @if($instructionDossiersChefFiliere->isNotEmpty())
                            <a href="{{ route('ca.workflow.instruction-dossiers.index') }}" class="btn btn-sm btn-outline-secondary mt-1">Liste agence — dossiers en attente</a>
                        @endif
                    </div>
                </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Analyse critique</strong>
                </div>
                <div class="card-body">
                    @forelse($eer->entreprise?->dossierAnalyseCritique?->avis ?? [] as $avis)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $avis->source_label ?? $avis->source_type }}</strong>
                                <span class="badge bg-secondary">{{ $avis->etat }}</span>
                            </div>
                            <div class="small text-muted mb-2">
                                {{ $avis->emisPar?->name ?? 'Systeme' }} · {{ optional($avis->emis_at)->format('d/m/Y H:i') ?? '-' }}
                            </div>
                            <div style="white-space: pre-wrap;">{{ $avis->contenu }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun avis consolidé.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <strong>Décision chef d'agence</strong>
                </div>
                <div class="card-body">
                    @php($structPres = \App\Models\DossierEntreeRelation::clientStructurationPresentation($eer))
                    <p class="mb-1"><strong>État structuration client :</strong> <span class="badge text-bg-{{ $structPres['badge_variant'] }}">{{ $structPres['label'] }}</span></p>
                    @if($structPres['detail'])
                        <p class="small text-muted mb-2">{{ e($structPres['detail']) }}</p>
                    @endif
                    @if($eer->isClientStructurationNonStructure())
                        <p class="small text-muted mb-2 mb-lg-3"><span class="badge bg-light text-dark border">Non structuré</span> regroupe les états « en attente », « en cours » et « rejetée » tant que le chef d’agence n’a pas validé.</p>
                    @endif
                    <p><strong>Statut EER :</strong> {{ $eer->statut_libelle }}</p>
                    <p><strong>Validation structuration :</strong> {{ $eer->instruction_validation_status }}</p>
                    <p><strong>Soumis par le chef de filière :</strong> {{ optional($eer->programmes_submitted_at)->format('d/m/Y H:i') ?? '-' }}</p>
                    @if($eer->qualification_rejected_by_agence_at && $eer->programmes_submitted_at === null && ! $eer->qualification_validated_by_agence_at)
                        <p class="text-danger small mb-2"><strong>Dernier refus le</strong> {{ $eer->qualification_rejected_by_agence_at->format('d/m/Y H:i') }}
                            @if($eer->qualificationRejectedByAgenceUser) — {{ $eer->qualificationRejectedByAgenceUser->name }} @endif
                            @if($eer->qualification_reject_motif)<br><span class="text-body">{{ e($eer->qualification_reject_motif) }}</span>@endif
                        </p>
                    @endif
                    @if($eer->qualification_validated_by_agence_at)
                        <p class="text-success mb-3"><strong>Structuration validée le</strong> {{ $eer->qualification_validated_by_agence_at->format('d/m/Y H:i') }}
                            @if($eer->qualificationValidatedByAgenceUser)
                                — {{ $eer->qualificationValidatedByAgenceUser->name }}
                            @endif
                        </p>
                    @endif
                    @if(!$eer->qualification_validated_by_agence_at && !$eer->instruction_validated_at)
                        @if($eer->programmes_submitted_at && $eer->statut === \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
                            <button type="button" class="btn btn-outline-danger me-2 mb-2" data-bs-toggle="modal" data-bs-target="#modalCaRejectQualificationWorkflow">Refuser la structuration</button>
                        @endif
                        @if($eer->programmeSelections->isNotEmpty())
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCaApproveInstructionLegacy">
                                Valider et créer les dossiers (ancien flux)
                            </button>
                        @else
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalCaApproveInstruction">
                                Valider la structuration
                            </button>
                        @endif
                    @else
                        @if($eer->qualification_validated_by_agence_at && $instructionDossiersChefFiliere->contains(fn ($d) => $d->isInstructionPendingAgenceValidation()))
                            <a href="{{ route('ca.workflow.instruction-dossiers.index') }}" class="btn btn-warning btn-sm text-dark">Dossiers d’instruction à valider</a>
                            <p class="text-muted small mt-2 mb-0">Une ou plusieurs soumissions du chef de filière sont en attente.</p>
                        @else
                            <p class="text-muted mb-0">Aucune action de structuration à traiter sur cet écran.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(!$eer->qualification_validated_by_agence_at && !$eer->instruction_validated_at && $eer->programmes_submitted_at && $eer->statut === \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
<div class="modal fade" id="modalCaRejectQualificationWorkflow" tabindex="-1" aria-labelledby="modalCaRejectQualificationWorkflowLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('ca.workflow.instructions.reject-qualification', $eer->token) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCaRejectQualificationWorkflowLabel">Refuser la structuration</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Le chef de filière pourra modifier la structuration et la resoumettre.</p>
                    <label for="reject_qualif_motif_wf" class="form-label small">Motif (optionnel)</label>
                    <textarea class="form-control" name="reject_motif" id="reject_qualif_motif_wf" rows="3" maxlength="5000" placeholder="Précisez les éléments à corriger…"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer le refus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@if(!$eer->qualification_validated_by_agence_at && !$eer->instruction_validated_at)
    @if($eer->programmeSelections->isNotEmpty())
    <div class="modal fade" id="modalCaApproveInstructionLegacy" tabindex="-1" aria-labelledby="modalCaApproveInstructionLegacyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('ca.workflow.instructions.approve', $eer->token) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCaApproveInstructionLegacyLabel">Valider (ancien flux)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Valider et créer les dossiers d’instruction pour les programmes listés (ancien flux) ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @else
    <div class="modal fade" id="modalCaApproveInstruction" tabindex="-1" aria-labelledby="modalCaApproveInstructionLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="post" action="{{ route('ca.workflow.instructions.approve', $eer->token) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCaApproveInstructionLabel">Valider la structuration</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Confirmer la validation de la structuration ? Le chef de filière constituera ensuite le dossier d’instruction multi-programmes (programmes et budgets) sur la fiche client, puis vous le validerez dans « Dossiers d’instruction (multi-programmes) ».</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Confirmer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endif
@endsection
