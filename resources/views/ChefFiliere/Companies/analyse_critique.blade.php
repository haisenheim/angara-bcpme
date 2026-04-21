@extends('Layouts.chef_filiere')

@section('title', 'Analyse critique — '.$item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.qualifications.show', $item->token) }}">Qualification</a></li>
        <li class="breadcrumb-item active" aria-current="page">Analyse critique</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Analyse critique</h5>
    <p class="text-body-secondary mb-0 mt-1 small">{{ $item->name }} — synthèse et avis consolidés (lecture seule).</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <a href="{{ route('chef-filiere.qualifications.show', $item->token) }}" class="cf-back-link">
        <i class="demo-pli-arrow-left" aria-hidden="true"></i> Retour à la qualification
    </a>

    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Dossier d'analyse critique</h1>
        <p class="cf-hero__lead">Vue consolidée des positions et de la synthèse pour ce dossier.</p>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card cf-client-card h-100">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-semibold">Synthèse</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small text-uppercase fw-semibold mb-1">Statut</p>
                    <p class="mb-4">{{ $analyseCritique?->statut ?? '—' }}</p>
                    <p class="text-muted small text-uppercase fw-semibold mb-1">Synthèse consolidée</p>
                    <div class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap;">{{ $analyseCritique?->synthese ?: '—' }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card cf-client-card h-100">
                <div class="card-header py-3">
                    <h6 class="mb-0 fw-semibold">Avis consolidés</h6>
                </div>
                <div class="card-body">
                    @forelse($analyseCritique?->avis ?? [] as $avis)
                        <div class="border rounded-3 p-3 mb-3 bg-white">
                            <div class="d-flex justify-content-between gap-3 flex-wrap mb-2">
                                <div>
                                    <div class="fw-semibold">{{ $avis->source_label ?? $avis->source_type }}</div>
                                    <div class="small text-muted">{{ $avis->source_type }}</div>
                                </div>
                                <div class="text-end">
                                    <span class="badge rounded-pill bg-secondary">{{ $avis->etat }}</span>
                                    <div class="small text-muted mt-1">{{ optional($avis->emis_at)->format('d/m/Y H:i') ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="small text-muted mb-2">{{ $avis->emisPar?->name ?? 'Système' }}</div>
                            <div style="white-space: pre-wrap;">{{ $avis->contenu }}</div>
                        </div>
                    @empty
                        <p class="text-muted mb-0">Aucun avis n'a encore été consolidé.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
