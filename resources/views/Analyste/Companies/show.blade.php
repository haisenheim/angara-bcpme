@extends('Layouts.analyste')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
@endpush

@include('partials.entreprise-fiche-styles')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('analyste.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="analysteEntrepriseShowActions">
        <li><a class="dropdown-item" href="{{ route('analyste.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i> Grille des engagements</a></li>
        @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
        <li><a class="dropdown-item" href="{{ route('analyste.entreprise.questionnaire', $item->token) }}"><i class="demo-psi-pen-5 me-2"></i> Questionnaire de mise en relation</a></li>
        <li><a class="dropdown-item" href="{{ route('analyste.entreprise.physique.create', $item->token) }}"><i class="demo-psi-add-user me-2"></i> Tiers personne physique</a></li>
        <li><a class="dropdown-item" href="{{ route('analyste.entreprise.morale.create', $item->token) }}"><i class="demo-psi-building me-2"></i> Tiers personne morale</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        @if($item->prospect)
            <span class="badge bg-warning text-dark">Prospect</span>
        @else
            <span class="badge bg-success">Client</span>
        @endif
        <span class="badge bg-secondary">{{ $item->taille ?? '—' }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere ?? '—' }}</span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">Portefeuille — {{ $item->forme?->name ?? '—' }} — {{ $item->agence?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

@php
    $eer = $item->dossierEntreeRelation;
    $qualifUrl = null;
    $isQualifAuthor = $eer && (int) auth()->id() === (int) $eer->qualification_user_id;
    $hasCompletedQualif = $eer && $eer->qualification_completed_at;
    $showQualifDetail = $eer && (
        $eer->qualification_completed_at
        || $eer->programmes_submitted_at
        || $eer->qualification_validated_by_agence_at
        || in_array($eer->statut, [
            \App\Models\DossierEntreeRelation::STATUT_QUALIFIE,
            \App\Models\DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION,
            \App\Models\DossierEntreeRelation::STATUT_QUALIFICATION_AGENCE_VALIDEE,
            \App\Models\DossierEntreeRelation::STATUT_INSTRUCTION_VALIDEE,
        ], true)
    );
@endphp

@include('partials.qualification-section-compact', [
    'item' => $item,
    'eer' => $eer,
    'qualifUrl' => $qualifUrl,
    'hasCompletedQualif' => $hasCompletedQualif,
    'isQualifAuthor' => $isQualifAuthor,
    'showQualifDetail' => $showQualifDetail,
    'workspace' => 'analyste',
])

@if($dossiersAnalyste->isNotEmpty())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent border-0 py-3 d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold"><i class="demo-psi-folder me-2 text-primary"></i>Mes dossiers d'instruction</h6>
            <a href="{{ route('analyste.dossiers.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($dossiersAnalyste as $dossier)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border h-100">
                            <div class="card-body d-flex align-items-center justify-content-between">
                                <div>
                                    <strong>{{ $dossier->programme?->name ?? 'Programme' }}</strong>
                                    <small class="d-block text-muted">{{ $dossier->programme?->signataire ?? '' }}</small>
                                </div>
                                <a href="{{ route('analyste.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">
                                    <i class="demo-psi-eye me-1"></i> Voir
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@include('partials.entreprise-fiche-client-ca-body', [
    'item' => $item,
    'mr' => $mr,
    'checklist' => $checklist,
    'dossierShowRoute' => 'analyste.dossiers.show',
    'programmeShowRoute' => 'analyste.programmes.show',
    'tiersEntrepriseShowRoute' => 'analyste.entreprises.show',
])
@endsection
