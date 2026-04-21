@extends('Layouts.chef_filiere')

@section('title', 'Dossier d\'instruction')

@php $st = $dossier->status; @endphp

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.instructions.in-progress') }}">Instruction en cours</a></li>
        <li class="breadcrumb-item active" aria-current="page">Détail</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Dossier d'instruction</h5>
    <p class="text-body-secondary mb-0 mt-1 small">{{ $dossier->entreprise?->name ?? '—' }} — {{ $dossier->programme?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <a href="{{ route('chef-filiere.instructions.in-progress') }}" class="cf-back-link">
        <i class="demo-pli-arrow-left" aria-hidden="true"></i> Retour à la liste
    </a>

    <div class="cf-dossier-hero">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1 class="h4 mb-2 fw-bold text-dark">{{ $dossier->entreprise?->name ?? '—' }}</h1>
                <p class="text-muted mb-0">{{ $dossier->programme?->name ?? '—' }}</p>
            </div>
            <div>
                <span class="badge rounded-pill bg-secondary fs-6 px-3 py-2">{{ $st['name'] ?? '—' }}</span>
            </div>
        </div>
    </div>

    <div class="cf-stat-grid">
        <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Client</div>
            <p class="cf-stat-tile__value">{{ $dossier->entreprise?->name ?? '—' }}</p>
        </div>
        <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Programme</div>
            <p class="cf-stat-tile__value">{{ $dossier->programme?->name ?? '—' }}</p>
        </div>
        <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Gestionnaire</div>
            <p class="cf-stat-tile__value">{{ $dossier->gestionnaire?->name ?? '—' }}</p>
        </div>
        <div class="cf-stat-tile">
            <div class="cf-stat-tile__label">Analyste</div>
            <p class="cf-stat-tile__value">{{ $dossier->analyste?->name ?? 'Non affecté' }}</p>
        </div>
    </div>
</div>
@endsection
