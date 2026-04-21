{{-- Historique chronologique des avis, validations et transmissions --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $timeline = $dossier->instructionWorkflowHistoryTimeline();
@endphp
<div class="card shadow-sm border-0 mb-4 border-start border-4 border-secondary">
    <div class="card-header bg-white py-3">
        <strong>Historique du dossier</strong>
        <p class="small text-muted mb-0 mt-1">Affectations, avis, validations et transmissions — date, heure et acteur lorsque l’information est disponible.</p>
    </div>
    <div class="card-body">
        @forelse($timeline as $row)
            @php
                $kind = $row['kind'] ?? 'event';
                $badgeClass = match ($kind) {
                    'avis' => 'bg-primary',
                    'validation' => 'bg-dark',
                    'affectation' => 'bg-info',
                    'transmission' => 'bg-success',
                    default => 'bg-secondary',
                };
            @endphp
            <div class="border-bottom pb-3 mb-3">
                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($kind) }}</span>
                    <span class="small text-muted">
                        {{ $row['at']->format('d/m/Y') }} à {{ $row['at']->format('H:i') }}
                    </span>
                </div>
                <p class="fw-semibold mb-1">{{ $row['label'] }}</p>
                @if(!empty($row['actor']))
                    <p class="small text-muted mb-2 mb-md-1">
                        <span class="text-uppercase fw-semibold">Auteur / acteur :</span>
                        <strong>{{ $row['actor']->name }}</strong>
                        @if($row['actor']->email)
                            <span class="d-none d-md-inline"> — {{ $row['actor']->email }}</span>
                        @endif
                    </p>
                @else
                    <p class="small text-muted mb-2 mb-md-1">Auteur non renseigné ou non applicable.</p>
                @endif
                @if(!empty($row['body_html']))
                    <div class="mt-2 small rich-text-rendered border rounded p-3 bg-light">{!! $row['body_html'] !!}</div>
                @endif
            </div>
        @empty
            <p class="text-muted small mb-0">Aucun événement tracé pour ce dossier.</p>
        @endforelse
    </div>
</div>
