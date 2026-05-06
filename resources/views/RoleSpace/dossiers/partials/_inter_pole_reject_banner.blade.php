{{--
    Bandeau d'alerte affiché côté responsable d'un pôle lorsque le pôle suivant a renvoyé le dossier.

    Variables attendues :
    - $rejected      : bool — true si le rejet inter-pôle est actif
    - $motif         : string|null — motif saisi par le responsable du pôle suivant
    - $rejectedAt    : Carbon|null — horodatage du rejet
    - $rejectedBy    : User|null — utilisateur ayant rejeté
    - $libelleAction : string — verbe d'action attendu (ex. "modifier votre avis et retransmettre")
    - $sourcePole    : string — nom du pôle qui a renvoyé (ex. "responsable juridique")
--}}
@php
    $rejected = $rejected ?? false;
    $motif = $motif ?? null;
    $rejectedAt = $rejectedAt ?? null;
    $rejectedBy = $rejectedBy ?? null;
    $libelleAction = $libelleAction ?? 'modifier votre avis et retransmettre';
    $sourcePole = $sourcePole ?? 'pôle suivant';
@endphp
@if($rejected)
    <div class="alert alert-danger border-danger shadow-sm mb-3" role="alert">
        <h6 class="alert-heading mb-2">
            <i class="demo-psi-arrow-back me-1" aria-hidden="true"></i>Dossier renvoyé par le {{ $sourcePole }} (rejet inter-pôle)
        </h6>
        <p class="small mb-2">
            Le {{ $sourcePole }} a rejeté le dossier et vous le renvoie pour révision
            @if($rejectedAt)
                le <strong>{{ $rejectedAt->format('d/m/Y') }}</strong> à <strong>{{ $rejectedAt->format('H:i') }}</strong>
            @endif
            @if($rejectedBy)
                — par <strong>{{ $rejectedBy->name }}</strong>
            @endif.
        </p>
        @if($motif)
            <div class="border rounded p-2 bg-white small mb-2">
                <p class="mb-1 fw-semibold text-uppercase text-muted small">Motif</p>
                <p class="mb-0">{{ $motif }}</p>
            </div>
        @endif
        <p class="small mb-0 text-body-secondary">Vous pouvez désormais {{ $libelleAction }}.</p>
    </div>
@endif
