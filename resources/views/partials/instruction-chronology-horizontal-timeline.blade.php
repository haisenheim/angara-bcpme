{{--
  Timeline horizontale (événements, date/heure, auteur, profil — sans body_html).
  Variables : $rows (iterable), $useFullChronology (bool).
--}}
@php
    $__rows = collect($rows ?? []);
@endphp
@once
@push('styles')
<style>
    .instruction-chronology-h__scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: .35rem;
        margin-left: -.25rem;
        margin-right: -.25rem;
    }
    .instruction-chronology-h__track {
        display: flex;
        flex-direction: row;
        flex-wrap: nowrap;
        align-items: center;
        gap: 0;
        min-width: min-content;
        padding: .35rem .5rem .5rem;
    }
    .instruction-chronology-h__step {
        flex: 0 0 auto;
        width: 11.75rem;
        max-width: 44vw;
        text-align: center;
    }
    .instruction-chronology-h__step--card {
        border-radius: .5rem;
        padding: .5rem .4rem .65rem;
        transition: box-shadow .18s ease, border-color .18s ease, background-color .18s ease;
        border: 1px solid transparent;
    }
    .instruction-chronology-h__step--card:focus-within {
        border-color: var(--bs-border-color);
        outline: 0;
    }
    .instruction-chronology-h__step--card:hover {
        border-color: var(--bs-border-color);
        background-color: var(--bs-tertiary-bg);
        box-shadow: 0 .15rem .35rem rgba(0, 0, 0, .07);
    }
    .instruction-chronology-h__connector {
        flex: 1 0 1.25rem;
        min-width: 1.25rem;
        max-width: 5rem;
        height: 2px;
        border: 0;
        margin: 0;
        border-radius: 2px;
        background: var(--bs-border-color);
        opacity: .75;
    }
    .instruction-chronology-h__dot {
        width: 2rem;
        height: 2rem;
        margin-left: auto;
        margin-right: auto;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.92rem;
        line-height: 1;
        position: relative;
        z-index: 1;
        box-shadow: 0 .1rem .25rem rgba(0, 0, 0, .1);
    }
    .instruction-chronology-h__dot .bi {
        line-height: 1;
    }
    .instruction-chronology-h__dot-nofill {
        font-size: 0.55rem;
        opacity: .65;
    }
    .instruction-chronology-h__legend .badge {
        font-weight: 500;
    }
</style>
@endpush
@endonce

@if($__rows->isEmpty())
    <p class="text-muted small mb-0">Aucun événement enregistré pour ce dossier.</p>
@else
    <p class="text-muted small mb-2 d-md-none mb-md-3"><i class="bi bi-arrow-left-right me-1" aria-hidden="true"></i>Faites défiler horizontalement pour parcourir toute la chronologie.</p>
    <div class="instruction-chronology-h__scroll">
        <div class="instruction-chronology-h__track" role="list">
            @foreach($__rows as $row)
                @php
                    $kind = $row['kind'] ?? 'event';
                    $dotScheme = match ($kind) {
                        'analyse_critique' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary', 'border' => 'border-primary'],
                        'avis' => ['bg' => 'bg-info-subtle', 'text' => 'text-info', 'border' => 'border-info'],
                        'validation' => ['bg' => 'bg-dark-subtle', 'text' => 'text-dark', 'border' => 'border-dark'],
                        'affectation' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'border' => 'border-secondary'],
                        'transmission' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'border' => 'border-success'],
                        'creation' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning-emphasis', 'border' => 'border-warning'],
                        default => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'border' => 'border-secondary'],
                    };
                    $dotIcon = $useFullChronology
                        ? match ($kind) {
                            'analyse_critique' => 'bi-clipboard2-pulse',
                            'avis' => 'bi-chat-left-text',
                            'validation' => 'bi-patch-check',
                            'affectation' => 'bi-person-badge',
                            'transmission' => 'bi-send',
                            'creation' => 'bi-folder-plus',
                            default => 'bi-circle-fill',
                        }
                        : null;
                    $at = $row['at'] ?? null;
                    if ($at !== null && $at !== '' && ! ($at instanceof \Carbon\Carbon)) {
                        try {
                            $at = \Carbon\Carbon::parse($at);
                        } catch (\Throwable) {
                            $at = null;
                        }
                    }
                    $labelRaw = trim((string) ($row['label'] ?? '—'));
                    $labelDisplay = trim(preg_replace('/\s*\([^)]*\)/u', '', $labelRaw));
                    if ($labelDisplay === '') {
                        $labelDisplay = $labelRaw !== '' ? $labelRaw : '—';
                    }
                    $actor = $row['actor'] ?? null;
                    $roleName = $row['actor_role'] ?? ($actor?->role?->name ?? null);
                    $dateStr = $at ? $at->format('d/m/Y à H:i') : null;
                    $kindLabel = match ($kind) {
                        'analyse_critique' => 'Avis associé',
                        'creation' => 'Création',
                        default => ucfirst((string) $kind),
                    };
                    $tooltipLine = collect([
                        $labelDisplay,
                        $dateStr,
                        $actor?->name,
                        $roleName,
                        $useFullChronology ? $kindLabel : null,
                    ])->filter()->implode(' — ');
                    $tooltip = e(\Illuminate\Support\Str::limit($tooltipLine, 280));
                @endphp
                @if(! $loop->first)
                    <div class="instruction-chronology-h__connector" aria-hidden="true"></div>
                @endif
                <div class="instruction-chronology-h__step instruction-chronology-h__step--card" role="listitem" title="{{ $tooltip }}">
                    <div class="instruction-chronology-h__dot border {{ $dotScheme['bg'] }} {{ $dotScheme['text'] }} {{ $dotScheme['border'] }}" aria-hidden="true">
                        @if($dotIcon)
                            <i class="bi {{ $dotIcon }}" aria-hidden="true"></i>
                        @else
                            <span class="instruction-chronology-h__dot-nofill" aria-hidden="true">●</span>
                        @endif
                    </div>
                    @if($useFullChronology)
                        <div class="mt-1 mb-1">
                            <span class="badge bg-light text-dark border small">{{ match ($kind) {
                                'analyse_critique' => 'Avis associé',
                                'creation' => 'Création',
                                default => ucfirst((string) $kind),
                            } }}</span>
                            @if(! empty($row['avis_source']))
                                <span class="badge bg-light text-dark border small">{{ \Illuminate\Support\Str::limit((string) $row['avis_source'], 14) }}</span>
                            @endif
                        </div>
                    @endif
                    <div class="small fw-semibold text-break lh-sm mt-1">{{ $labelDisplay }}</div>
                    @if($at)
                        <div class="text-muted mt-1" style="font-size: 0.72rem;">
                            <time datetime="{{ $at->toIso8601String() }}">{{ $at->format('d/m/Y') }}</time><br>
                            <span class="text-nowrap">{{ $at->format('H:i') }}</span>
                        </div>
                    @endif
                    @if($actor)
                        <div class="text-muted mt-2 small text-break">
                            <span class="fw-semibold text-body">{{ $actor->name }}</span>
                            @if($roleName)
                                <span class="d-block mt-1 text-secondary" style="font-size: 0.72rem;">{{ $roleName }}</span>
                            @endif
                        </div>
                    @else
                        <div class="text-muted mt-2" style="font-size: 0.72rem;">Auteur non renseigné</div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @if($useFullChronology)
        <div class="instruction-chronology-h__legend d-flex flex-wrap gap-2 align-items-center justify-content-center justify-content-md-start mt-3 pt-3 border-top border-secondary border-opacity-25">
            <span class="small text-muted text-uppercase fw-semibold me-1">Légende</span>
            <span class="badge rounded-pill bg-primary-subtle text-primary border border-primary-subtle"><i class="bi bi-clipboard2-pulse me-1" aria-hidden="true"></i>Avis associé</span>
            <span class="badge rounded-pill bg-info-subtle text-info border border-info-subtle"><i class="bi bi-chat-left-text me-1" aria-hidden="true"></i>Avis</span>
            <span class="badge rounded-pill bg-dark-subtle text-dark border border-dark-subtle"><i class="bi bi-patch-check me-1" aria-hidden="true"></i>Décision</span>
            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle"><i class="bi bi-person-badge me-1" aria-hidden="true"></i>Affectation</span>
            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle"><i class="bi bi-send me-1" aria-hidden="true"></i>Transmission</span>
            <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis border border-warning-subtle"><i class="bi bi-folder-plus me-1" aria-hidden="true"></i>Création</span>
        </div>
    @endif
@endif
