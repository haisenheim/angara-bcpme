{{--
    Section qualification compacte — chef de filière ou chef d’agence (CA).
    Variables : $item, $eer, $qualifUrl (route écran qualification chef filière), $hasCompletedQualif, $isQualifAuthor, $showQualifDetail
    Option : $workspace = 'chef-filiere' | 'ca'
--}}
@php
    $workspace = $workspace ?? 'chef-filiere';
    $qualificationContext = match ($workspace) {
        'chef-filiere' => 'chef-filiere',
        'ca' => 'ca',
        'respexp', 'juridique', 'analyste-juridique' => 'respexp',
        'analyste' => 'analyste',
        default => 'ca',
    };
    $fmt = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y à H:i') : null;
    $showCaEntrepriseDecision = $item && ($item->promu_client_at || $item->prospect_rejected_at);
    $workflowInstructionUrl = ($eer && $eer->token) ? route('ca.workflow.instructions.show', $eer->token) : null;
    $caCanValidate = $workspace === 'ca'
        && $eer
        && $eer->programmes_submitted_at
        && ! $eer->qualification_validated_by_agence_at
        && $eer->statut === \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION;

    $statusLabel = '—';
    $statusBadge = 'secondary';
    $statusAt = null;
    $statusAtLabel = '';

    if ($eer && $showQualifDetail) {
        if ($eer->instruction_validated_at && $eer->statut === \App\Models\DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE) {
            $statusLabel = 'Instruction validée (CA)';
            $statusBadge = 'success';
            $statusAt = $fmt($eer->instruction_validated_at);
            $statusAtLabel = 'Validation instruction';
        } elseif ($eer->qualification_validated_by_agence_at) {
            $statusLabel = 'Qualification validée (chef d\'agence)';
            $statusBadge = 'success';
            $statusAt = $fmt($eer->qualification_validated_by_agence_at);
            $statusAtLabel = 'Validée le';
        } elseif ($eer->programmes_submitted_at && ! $eer->qualification_validated_by_agence_at) {
            $statusLabel = 'En attente du chef d\'agence';
            $statusBadge = 'warning';
            $statusAt = $fmt($eer->programmes_submitted_at);
            $statusAtLabel = 'Soumise le';
        } elseif ($eer->qualification_completed_at) {
            $statusLabel = 'Enregistrée — à soumettre au CA';
            $statusBadge = 'info';
            $statusAt = $fmt($eer->qualification_completed_at);
            $statusAtLabel = 'Dernière sauvegarde';
        } else {
            $statusLabel = 'Brouillon';
            $statusBadge = 'secondary';
            $statusAt = null;
            $statusAtLabel = '';
        }
    } elseif ($eer) {
        $statusLabel = 'Non complétée';
        $statusBadge = 'light';
        $statusAt = null;
        $statusAtLabel = '';
    }
@endphp

<div class="cf-qual-compact card cf-client-card mb-4 border-0">
    <div class="cf-qual-strip px-3 py-2 border-bottom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex flex-wrap align-items-center gap-2 gap-md-3 min-w-0">
                <span class="fw-semibold text-body text-nowrap">Qualification</span>
                @if($showQualifDetail && $eer)
                    <span class="badge rounded-pill bg-{{ $statusBadge }} {{ $statusBadge === 'light' ? 'text-dark border' : '' }}">{{ $statusLabel }}</span>
                    @if($statusAt)
                        <span class="cf-qual-strip__time small text-muted text-nowrap">
                            <span class="cf-qual-strip__time-label">{{ $statusAtLabel }}</span>
                            <span class="fw-medium text-body">{{ $statusAt }}</span>
                        </span>
                    @endif
                @else
                    <span class="badge rounded-pill bg-light text-dark border">Non démarrée</span>
                @endif
            </div>
            <div class="d-flex flex-wrap gap-2 align-items-center flex-shrink-0">
                @if($workspace === 'chef-filiere')
                    @if($hasCompletedQualif && $isQualifAuthor && $eer && ! $eer->programmes_submitted_at)
                        <a href="{{ $qualifUrl }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                    @endif
                    @if($hasCompletedQualif && $isQualifAuthor && $eer && $eer->programmes_submitted_at && ! $eer->qualification_validated_by_agence_at)
                        <span class="small text-muted">Soumis — lecture seule</span>
                    @endif
                    @if(!$hasCompletedQualif)
                        <a href="{{ $qualifUrl }}" class="btn btn-sm btn-primary">Qualifier</a>
                    @endif
                    @if($showQualifDetail)
                        <a href="{{ $qualifUrl }}" class="btn btn-sm btn-link text-decoration-none">Écran qualification</a>
                    @endif
                @elseif($workspace === 'ca')
                    @if($caCanValidate && $workflowInstructionUrl)
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalCaQualifApprove">
                            Valider la qualification
                        </button>
                    @elseif($showQualifDetail && $eer && $workflowInstructionUrl)
                        <a href="{{ $workflowInstructionUrl }}" class="btn btn-sm btn-link text-decoration-none">Écran validation</a>
                    @endif
                @endif
            </div>
        </div>
        @if($showCaEntrepriseDecision)
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2 pt-2 border-top cf-qual-strip__entreprise-ca">
                @if($item->promu_client_at)
                    <span class="badge rounded-pill bg-success">Passage client (chef d’agence)</span>
                    <span class="badge rounded-pill bg-light text-dark border fw-normal">{{ $item->promuClientUser?->name ?? '—' }}</span>
                    <span class="small text-muted text-nowrap">{{ $fmt($item->promu_client_at) }}</span>
                @elseif($item->prospect_rejected_at)
                    <span class="badge rounded-pill bg-warning text-dark">Refus prospect (chef d’agence)</span>
                    <span class="badge rounded-pill bg-light text-dark border fw-normal">{{ $item->prospectRejectedUser?->name ?? '—' }}</span>
                    <span class="small text-muted text-nowrap">{{ $fmt($item->prospect_rejected_at) }}</span>
                @endif
            </div>
        @endif
        <p class="small text-muted mb-0 mt-2 mt-md-1">
            @if($workspace === 'ca')
                Une qualification par client. Après votre validation, le chef de filière inscrit le client aux programmes (dossiers d’instruction créés à l’inscription).
            @elseif(in_array($workspace, ['respexp', 'juridique', 'analyste-juridique', 'analyste'], true))
                Synthèse de la qualification (lecture seule). L’instruction des dossiers est assurée par l’analyste financier une fois le dossier affecté.
            @else
                Une qualification par client. Inscriptions programme après validation du chef d’agence.
            @endif
        </p>
    </div>
    <div class="card-body p-3">
        @if($showQualifDetail)
            <details class="cf-qual-details">
                <summary class="cf-qual-details__summary small fw-semibold text-primary user-select-none">Afficher le détail (analyses, besoins, programmes)</summary>
                <div class="cf-qual-details__body pt-3 border-top mt-2">
                    @include('partials.entreprise-qualification-chef-filiere', [
                        'item' => $item,
                        'qualificationContext' => $qualificationContext,
                        'stripOuterCard' => true,
                        'hideMetaRow' => true,
                    ])
                </div>
            </details>
        @else
            @if($workspace === 'chef-filiere')
                <p class="text-body-secondary small mb-0">Aucune qualification enregistrée. Utilisez « Qualifier » pour démarrer.</p>
            @else
                <p class="text-body-secondary small mb-0">Aucune qualification enregistrée. La saisie est effectuée par le chef de filière.</p>
            @endif
        @endif
    </div>
</div>

@if($workspace === 'ca' && $caCanValidate && $eer && $item)
<div class="modal fade" id="modalCaQualifApprove" tabindex="-1" aria-labelledby="modalCaQualifApproveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="{{ route('ca.workflow.instructions.approve', $eer->token) }}">
                @csrf
                <input type="hidden" name="return_entreprise_token" value="{{ $item->token }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCaQualifApproveLabel">Valider la qualification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Confirmer la validation de la qualification pour <strong>{{ $item->name }}</strong> ?</p>
                    <p class="small text-muted mt-2 mb-0">Après validation, le statut est mis à jour et le chef de filière pourra inscrire le client aux programmes depuis la fiche client.</p>
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
