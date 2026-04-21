@extends('Layouts.chef_filiere')

@section('title', 'Espace chef de filière')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Tableau de bord</h5>
    <p class="text-body-secondary mb-0 mt-1 small">Pilotage de la qualification et des dossiers d'instruction de votre agence.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Vue d'ensemble</h1>
        <p class="cf-hero__lead">Accédez rapidement aux files d'attente et au portefeuille clients.</p>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric">{{ $pendingQualif }}</div>
                    <p class="cf-dash-card__label">Qualifications en attente</p>
                    <a href="{{ route('chef-filiere.qualifications.index') }}" class="btn btn-primary btn-sm align-self-start">Ouvrir la file</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric">{{ $clientsCount }}</div>
                    <p class="cf-dash-card__label">Clients (agence)</p>
                    <a href="{{ route('chef-filiere.clients.index') }}" class="btn btn-outline-primary btn-sm align-self-start">Voir la liste</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric">{{ $instructionPending }}</div>
                    <p class="cf-dash-card__label">Instruction — en attente (chef d'agence)</p>
                    <a href="{{ route('chef-filiere.instructions.pending') }}" class="btn btn-outline-primary btn-sm align-self-start">Consulter</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="cf-dash-card h-100">
                <div class="cf-dash-card__body">
                    <div class="cf-dash-card__metric">{{ $instructionEnCours }}</div>
                    <p class="cf-dash-card__label">Dossiers d'instruction en cours</p>
                    <a href="{{ route('chef-filiere.instructions.in-progress') }}" class="btn btn-outline-primary btn-sm align-self-start">Consulter</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 cf-panel">
        <div class="cf-panel__toolbar">
            <p class="cf-panel__toolbar-label mb-0">Documentation</p>
        </div>
        <div class="p-3 p-md-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="h6 mb-1 fw-semibold">Référentiel programmes</h2>
                <p class="text-muted small mb-0">Consultez les fiches signalétiques et critères d'éligibilité.</p>
            </div>
            <a href="{{ route('chef-filiere.programmes.index') }}" class="btn btn-sm btn-primary">Ouvrir les programmes</a>
        </div>
    </div>
</div>
@endsection
