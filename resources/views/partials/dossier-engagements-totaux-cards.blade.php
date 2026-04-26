{{-- Totaux saisis à la structuration (chef de filière) : engagements sollicités vs en cours --}}
@php
    /** @var \App\Models\Dossier $dossier */
    $fmtEngagementTotal = function ($v) {
        if ($v === null || $v === '') {
            return '—';
        }

        return number_format((float) $v, 0, ',', ' ').' XAF';
    };
@endphp
<div class="row g-3 mb-4 dossier-engagements-totaux">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Total engagements sollicités</small>
                <p class="mb-0 fw-semibold fs-5">{{ $fmtEngagementTotal($dossier->engagements_sollicites_total ?? null) }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body py-3">
                <small class="text-muted text-uppercase d-block mb-1">Total engagements en cours</small>
                <p class="mb-0 fw-semibold fs-5">{{ $fmtEngagementTotal($dossier->engagements_en_cours_total ?? null) }}</p>
            </div>
        </div>
    </div>
</div>
