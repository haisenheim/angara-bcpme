{{-- Colonne latérale : état de validation/rejet de la structuration (chef d’agence) + actions. --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $pending = $dossier->isInstructionPendingAgenceValidation();
    $canAct = ($canApproveRejectInstructionTransmission ?? false) && $pending;
@endphp
<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-transparent border-0 py-3">
        <h6 class="mb-0 fw-semibold"><i class="demo-psi-check me-2 text-primary"></i>Décision agence (structuration)</h6>
        <p class="text-muted small mb-0 mt-1">Décision du chef d’agence de l’agence du dossier.</p>
    </div>
    <div class="card-body">
        <p class="mb-2"><span class="badge bg-light text-dark border">{{ $closureStatutLabel ?? '—' }}</span></p>

        @if($canAct)
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionApproveModal">
                    <i class="demo-psi-check me-1"></i> Valider la structuration
                </button>
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#caInstructionTransmissionRejectModal">
                    <i class="demo-psi-cross me-1"></i> Rejeter la structuration
                </button>
            </div>
        @elseif($pending && ! ($canApproveRejectInstructionTransmission ?? false))
            <p class="text-muted small mb-0">Vous n’êtes pas habilité à valider ou rejeter cette structuration pour ce dossier.</p>
        @endif

        @if(! empty($workflowBundleView))
            <hr class="my-3">
            <a href="{{ route('ca.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-outline-secondary w-100">Ouvrir la fiche dossier complète</a>
        @endif
    </div>
</div>

@if($canAct)
    @include('partials.ca-instruction-transmission-modals', ['dossier' => $dossier])
@endif
