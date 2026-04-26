@extends('Layouts.ca')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
@endpush

@include('partials.entreprise-fiche-styles')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="caEntrepriseShowActions">
        <li><a class="dropdown-item" href="{{ route('ca.entreprises.index') }}">Retour liste entreprises</a></li>
        <li><a class="dropdown-item" href="{{ route('ca.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>État des engagements</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        <span class="badge bg-success">Client</span>
        <span class="badge bg-secondary">{{ $item->taille ?? '—' }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere ?? '—' }}</span>
    </div>
    <p class="text-body-secondary mb-0 mt-1">Portefeuille client — {{ $item->forme?->name ?? '—' }} — {{ $item->agence?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
@endif
@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
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
    'workspace' => 'ca',
])

@include('partials.entreprise-fiche-client-ca-body', compact('item', 'mr', 'checklist'))
@endsection
