{{--
    Bandeau d'alerte affiché côté analyste lorsque sa soumission a été rejetée par son responsable.
    Indique le motif, l'auteur et l'horodatage du rejet, et invite à corriger puis retransmettre.

    Variables attendues :
    - $rejected      : bool — true si la soumission est dans un état rejeté
    - $motif         : string|null — motif saisi par le responsable
    - $rejectedAt    : Carbon|null — horodatage du rejet
    - $rejectedBy    : User|null — utilisateur ayant rejeté
    - $libelleAction : string — verbe d'action attendu (ex. "modifier vos rubriques d'analyse")
--}}
@php
    $rejected = $rejected ?? false;
    $motif = $motif ?? null;
    $rejectedAt = $rejectedAt ?? null;
    $rejectedBy = $rejectedBy ?? null;
    $libelleAction = $libelleAction ?? 'corriger votre saisie puis la retransmettre';
@endphp
@if($rejected)
    <div class="alert alert-warning border-warning shadow-sm mb-3" role="alert">
        <h6 class="alert-heading mb-2">
            <i class="demo-psi-warning me-1" aria-hidden="true"></i>Soumission rejetée par votre responsable
        </h6>
        <p class="small mb-2">
            Votre soumission précédente a été rejetée
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
