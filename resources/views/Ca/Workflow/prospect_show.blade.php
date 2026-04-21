@extends('Layouts.ca')

@include('partials.entreprise-fiche-styles')

@push('styles')
<style>
    .wf-prospect-hero {
        border-radius: 1rem;
        border: 1px solid rgba(36, 68, 127, 0.12);
        background: linear-gradient(135deg, rgba(36, 68, 127, 0.06), #fff);
    }
    .wf-step {
        flex: 1 1 0;
        min-width: 7rem;
        padding: 0.85rem 0.75rem;
        border-radius: 0.85rem;
        border: 1px solid #e5e7eb;
        background: #fff;
        text-align: center;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .wf-step--done {
        border-color: rgba(136, 184, 36, 0.45);
        background: rgba(136, 184, 36, 0.08);
        box-shadow: 0 1px 0 rgba(136, 184, 36, 0.2);
    }
    .wf-step--current {
        border-color: rgba(36, 68, 127, 0.35);
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
    }
    .wf-step--wait {
        opacity: 0.88;
    }
    .wf-avis-card {
        border-radius: 1rem;
        border: 1px solid #e5e7eb;
        overflow: hidden;
        height: 100%;
    }
    .wf-avis-card__head {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #eef2f7;
        background: #f8fafc;
    }
    .wf-avis-card__body {
        padding: 1.1rem 1.25rem;
        max-height: 22rem;
        overflow-y: auto;
    }
    .wf-decision-panel {
        border-radius: 1rem;
        border: 1px solid rgba(136, 184, 36, 0.28);
        background: linear-gradient(180deg, rgba(136, 184, 36, 0.1), #fff);
    }
    @media (max-width: 767.98px) {
        .wf-avis-card__body { max-height: none; }
    }
</style>
@endpush

@section('title', $item->name.' — Arbitrage')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.workflow.prospects.index') }}">Arbitrage prospects</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 42) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
<div class="d-flex gap-2 flex-wrap">
    <a href="{{ route('ca.workflow.prospects.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="demo-psi-back me-1"></i> File d'arbitrage
    </a>
    <a href="{{ route('ca.entreprises.show', $item->token) }}" class="btn btn-sm btn-outline-primary">
        <i class="demo-psi-information me-1"></i> Fiche prospect complète
    </a>
</div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        <span class="badge bg-danger">Prospect</span>
        @if($item->prospect_rejected_at)
            <span class="badge bg-secondary">Refusé</span>
        @elseif($item->promu_client_at)
            <span class="badge bg-success">Promu client</span>
        @endif
    </div>
    <p class="text-body-secondary mb-0 small">Décision chef d'agence — promotion en client ou refus définitif</p>
</div>
@endsection

@section('content')
@php
    $fmtDt = static fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y H:i') : null;
    $submittedAt = $fmtDt($item->prospect_submitted_at);
    $hasJuridique = (bool) $item->juridique_avis_at;
    $hasConformite = (bool) $item->conformite_avis_at;
    $canDecide = $hasJuridique && $hasConformite && ! $item->prospect_rejected_at && ! $item->promu_client_at;
@endphp

<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="card wf-prospect-hero shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4 align-items-start">
                <div class="col-lg-8">
                    <p class="text-uppercase text-muted small fw-semibold mb-2">Synthèse dossier</p>
                    <p class="mb-3 text-body-secondary">
                        Vérifiez les avis juridique et conformité avant de valider le passage au statut <strong>client</strong> ou de refuser le prospect.
                    </p>
                    <div class="d-flex flex-wrap gap-3 small">
                        <div>
                            <span class="text-muted d-block">Agence</span>
                            <span class="fw-semibold">{{ $item->agence?->name ?? '—' }}</span>
                        </div>
                        <div class="vr d-none d-sm-block text-muted opacity-25"></div>
                        <div>
                            <span class="text-muted d-block">Soumission gestionnaire</span>
                            <span class="fw-semibold">{{ $submittedAt ?? '—' }}</span>
                        </div>
                        @if($item->region || $item->arrondissement)
                            <div class="vr d-none d-sm-block text-muted opacity-25"></div>
                            <div>
                                <span class="text-muted d-block">Localisation</span>
                                <span class="fw-semibold">{{ $item->arrondissement?->name ?? '—' }}@if($item->region) <span class="text-body-secondary">· {{ $item->region->name }}</span>@endif</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="rounded-3 border bg-white p-3 h-100">
                        <span class="text-uppercase text-muted small fw-semibold d-block mb-2">État des avis</span>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between gap-2 py-1 border-bottom border-light">
                                <span>Juridique</span>
                                @if($hasJuridique)
                                    <span class="badge bg-success">Rendu</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </li>
                            <li class="d-flex justify-content-between gap-2 py-1">
                                <span>Conformité</span>
                                @if($hasConformite)
                                    <span class="badge bg-success">Rendu</span>
                                @else
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4" role="list" aria-label="Étapes du circuit">
        <div class="wf-step wf-step--done" role="listitem">
            <div class="small text-muted text-uppercase mb-1">Étape 1</div>
            <div class="fw-semibold small">Soumission</div>
            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $submittedAt ?? '—' }}</div>
        </div>
        <div class="wf-step {{ $hasJuridique ? 'wf-step--done' : 'wf-step--wait wf-step--current' }}" role="listitem">
            <div class="small text-muted text-uppercase mb-1">Étape 2</div>
            <div class="fw-semibold small">Avis juridique</div>
            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $hasJuridique ? $fmtDt($item->juridique_avis_at) : 'En attente' }}</div>
        </div>
        <div class="wf-step {{ $hasConformite ? 'wf-step--done' : ($hasJuridique ? 'wf-step--wait wf-step--current' : 'wf-step--wait') }}" role="listitem">
            <div class="small text-muted text-uppercase mb-1">Étape 3</div>
            <div class="fw-semibold small">Avis conformité</div>
            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $hasConformite ? $fmtDt($item->conformite_avis_at) : 'En attente' }}</div>
        </div>
        <div class="wf-step {{ $canDecide ? 'wf-step--current' : ($hasJuridique && $hasConformite ? 'wf-step--done' : 'wf-step--wait') }}" role="listitem">
            <div class="small text-muted text-uppercase mb-1">Étape 4</div>
            <div class="fw-semibold small">Décision chef d'agence</div>
            <div class="text-muted mt-1" style="font-size: 0.75rem;">{{ $canDecide ? 'À statuer' : ($item->prospect_rejected_at ? 'Refus' : ($item->promu_client_at ? 'Validé' : '—')) }}</div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="wf-avis-card shadow-sm bg-white h-100">
                <div class="wf-avis-card__head d-flex justify-content-between align-items-start gap-2 flex-wrap">
                    <div>
                        <span class="fw-semibold"><i class="demo-psi-file-text me-2 text-primary"></i>Avis juridique</span>
                        @if($hasJuridique)
                            <div class="small text-muted mt-1">Rendu le {{ $fmtDt($item->juridique_avis_at) }}@if($item->juridiqueAvisUser) · {{ $item->juridiqueAvisUser->name }}@endif</div>
                        @endif
                    </div>
                    @if($hasJuridique)
                        <span class="badge bg-success align-self-start">Reçu</span>
                    @else
                        <span class="badge bg-warning text-dark align-self-start">En attente</span>
                    @endif
                </div>
                <div class="wf-avis-card__body">
                    @if($item->juridique_avis)
                        <div class="rich-text-rendered small">{!! $item->juridique_avis !!}</div>
                    @else
                        <p class="text-muted mb-0 small">Aucun contenu d'avis pour l'instant.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="wf-avis-card shadow-sm bg-white h-100">
                <div class="wf-avis-card__head d-flex justify-content-between align-items-start gap-2 flex-wrap">
                    <div>
                        <span class="fw-semibold"><i class="demo-psi-file-text me-2 text-primary"></i>Avis conformité</span>
                        @if($hasConformite)
                            <div class="small text-muted mt-1">Rendu le {{ $fmtDt($item->conformite_avis_at) }}@if($item->conformiteAvisUser) · {{ $item->conformiteAvisUser->name }}@endif</div>
                        @endif
                    </div>
                    @if($hasConformite)
                        <span class="badge bg-success align-self-start">Reçu</span>
                    @else
                        <span class="badge bg-warning text-dark align-self-start">En attente</span>
                    @endif
                </div>
                <div class="wf-avis-card__body">
                    @if($item->conformite_avis)
                        <div class="rich-text-rendered small">{!! $item->conformite_avis !!}</div>
                    @else
                        <p class="text-muted mb-0 small">Aucun contenu d'avis pour l'instant.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="wf-decision-panel shadow-sm p-4 mb-2">
        <div class="row g-4 align-items-start">
            <div class="col-lg-7">
                <h6 class="fw-semibold mb-2"><i class="demo-psi-check me-2 text-success"></i>Décision</h6>
                @if($item->prospect_rejected_at)
                    <div class="alert alert-danger border-0 mb-0">
                        <strong>Prospect refusé</strong> par <strong>{{ $item->prospectRejectedUser?->name ?? '—' }}</strong>
                        le {{ $fmtDt($item->prospect_rejected_at) }}.
                        Cette décision est définitive pour ce dossier.
                    </div>
                @elseif($item->promu_client_at)
                    <div class="alert alert-success border-0 mb-0">
                        <strong>Prospect promu client</strong> par <strong>{{ $item->promuClientUser?->name ?? '—' }}</strong>
                        le {{ $fmtDt($item->promu_client_at) }}.
                    </div>
                @elseif($canDecide)
                    <p class="text-body-secondary small mb-0">
                        Les deux avis sont disponibles. Vous pouvez promouvoir le prospect au statut client ou le refuser. Le refus est définitif et les responsables ne pourront plus modifier les avis.
                    </p>
                @else
                    <p class="text-body-secondary small mb-0">
                        <span class="badge bg-warning text-dark me-1">Action bloquée</span>
                        La décision n'est possible qu'une fois les avis juridique et conformité rendus.
                    </p>
                @endif
            </div>
            <div class="col-lg-5">
                @if($canDecide)
                    <div class="d-grid gap-2">
                        <form method="post" action="{{ route('ca.workflow.prospects.approve', $item->token) }}" onsubmit="return confirm('Valider ce prospect comme client ?');">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2">
                                <i class="demo-psi-check me-2"></i>Valider et promouvoir en client
                            </button>
                        </form>
                        <div class="border rounded-3 p-3 bg-white">
                            <form method="post" action="{{ route('ca.workflow.prospects.reject', $item->token) }}" onsubmit="return confirm('Refuser ce prospect ? Les avis ne pourront plus être modifiés.');">
                                @csrf
                                <label for="reject_motif" class="form-label small fw-semibold mb-1">Refuser le prospect</label>
                                <p class="small text-muted mb-2">Motif optionnel pour la traçabilité.</p>
                                <textarea name="reject_motif" id="reject_motif" class="form-control form-control-sm mb-3" rows="3" placeholder="Précisions…"></textarea>
                                <button type="submit" class="btn btn-outline-danger w-100">Refuser définitivement</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
