{{-- Décision du chef d'agence : promotion client ou refus prospect. Variables attendues : $item ou $entreprise --}}
@php
    $e = $item ?? $entreprise ?? null;
@endphp
@if($e && ($e->promu_client_at || $e->prospect_rejected_at))
    @php
        $dt = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y à H:i') : '—';
    @endphp
    @if($e->promu_client_at)
        <div class="alert alert-success border mb-3" role="status">
            <strong>Validation par le chef d'agence.</strong>
            Passage au statut client par <strong>{{ $e->promuClientUser?->name ?? '—' }}</strong>
            le <strong>{{ $dt($e->promu_client_at) }}</strong>.
        </div>
    @elseif($e->prospect_rejected_at)
        <div class="alert alert-warning border mb-3" role="status">
            <strong>Refus par le chef d'agence.</strong>
            Prospect non promu client par <strong>{{ $e->prospectRejectedUser?->name ?? '—' }}</strong>
            le <strong>{{ $dt($e->prospect_rejected_at) }}</strong>.
        </div>
    @endif
@endif
