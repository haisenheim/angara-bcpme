{{-- Une entrée de chronologie dossier d’instruction (workflow + analyse critique). Variables : $row, $showTimelineBody (défaut true) --}}
@php
    $showTimelineBody = $showTimelineBody ?? true;
    $kind = $row['kind'] ?? 'event';
    $badgeClass = match ($kind) {
        'analyse_critique' => 'bg-primary',
        'avis' => 'bg-info',
        'validation' => 'bg-dark',
        'affectation' => 'bg-secondary',
        'transmission' => 'bg-success',
        'creation' => 'bg-warning text-dark',
        default => 'bg-secondary',
    };
    $at = $row['at'] ?? null;
    if ($at !== null && $at !== '' && ! ($at instanceof \Carbon\Carbon)) {
        try {
            $at = \Carbon\Carbon::parse($at);
        } catch (\Throwable) {
            $at = null;
        }
    }
@endphp
<div class="border-bottom pb-3 mb-3">
    <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
        <span class="badge {{ $badgeClass }}">{{ match ($kind) {
            'analyse_critique' => 'Avis associé au dossier',
            'creation' => 'Création',
            default => ucfirst((string) $kind),
        } }}</span>
        @if(! empty($row['avis_source']))
            <span class="badge bg-light text-dark border">{{ $row['avis_source'] }}</span>
        @endif
        @if(! empty($row['avis_etat']))
            <span class="badge bg-light text-secondary border small">{{ $row['avis_etat'] }}</span>
        @endif
        @if($at)
            <span class="small text-muted">{{ $at->format('d/m/Y') }} à {{ $at->format('H:i') }}</span>
        @endif
    </div>
    <p class="fw-semibold mb-1">{{ $row['label'] ?? '—' }}</p>
    @php $actor = $row['actor'] ?? null; @endphp
    @if($actor)
        <p class="small text-muted mb-2 mb-md-1">
            <span class="text-uppercase fw-semibold">Auteur :</span>
            <strong>{{ $actor->name }}</strong>
            @if(! empty($row['actor_role']))
                <span class="text-muted">— {{ $row['actor_role'] }}</span>
            @elseif($actor->role?->name)
                <span class="text-muted">— {{ $actor->role->name }}</span>
            @endif
        </p>
    @else
        <p class="small text-muted mb-2 mb-md-1">Auteur non renseigné ou non applicable.</p>
    @endif
    @if($showTimelineBody && ! empty($row['body_html']))
        <div class="mt-2 small rich-text-rendered border rounded p-3 bg-body-tertiary">{!! $row['body_html'] !!}</div>
    @endif
</div>
