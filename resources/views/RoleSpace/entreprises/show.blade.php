@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    default => 'Layouts.app',
})

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
@endpush

@include('partials.entreprise-fiche-styles')

@section('title', $item->name.' - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <div class="dropdown">
        <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="demo-psi-dot-vertical me-1"></i> Actions
        </button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.index') }}">Retour liste entreprises</a></li>
            <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.pieces', $item->token) }}">Pièces exigibles</a></li>
        </ul>
    </div>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ Str::limit($item->name, 80) }}</h5>
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
    $qualWorkspace = match ($space['route'] ?? '') {
        'respexp' => 'respexp',
        'juridique' => 'juridique',
        'analyste-juridique' => 'analyste-juridique',
        default => 'ca',
    };
@endphp

@include('partials.qualification-section-compact', [
    'item' => $item,
    'eer' => $eer,
    'qualifUrl' => $qualifUrl,
    'hasCompletedQualif' => $hasCompletedQualif,
    'isQualifAuthor' => $isQualifAuthor,
    'showQualifDetail' => $showQualifDetail,
    'workspace' => $qualWorkspace,
])

@php
    $ficheProgrammeRoute = \Illuminate\Support\Facades\Route::has($space['route'].'.programmes.show')
        ? $space['route'].'.programmes.show'
        : false;
@endphp
@include('partials.entreprise-fiche-client-ca-body', [
    'item' => $item,
    'mr' => $mr,
    'checklist' => $checklist,
    'dossierShowRoute' => $space['route'].'.dossiers.show',
    'programmeShowRoute' => $ficheProgrammeRoute,
    'tiersEntrepriseShowRoute' => $space['route'].'.entreprises.show',
])
@endsection
