@extends('Layouts.ca')

@section('title', 'Dossier d’analyse critique — '.($item->entreprise?->name ?? 'Dossier'))

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.index') }}">Dossiers d’instruction</a></li>
        <li class="breadcrumb-item"><a href="{{ route('ca.dossiers.show', $item->token) }}">{{ $item->entreprise?->name ?? 'Dossier' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dossier d’analyse critique</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="caDossierAnalyseCritiqueActions" menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        <li>
            <a href="{{ route('ca.dossier.analyse-critique.synthese.pdf', $item->token) }}" class="dropdown-item" target="_blank" rel="noopener">
                <i class="demo-psi-download me-2"></i> Exporter le dossier en PDF
            </a>
        </li>
        <li>
            <a href="{{ route('ca.dossier.get.grille.analyse', $item->token) }}" class="dropdown-item">Grille et rubriques analyste (saisie)</a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a href="{{ route('ca.dossiers.show', $item->token) }}" class="dropdown-item">
                <i class="demo-pli-arrow-left me-2"></i> Retour à la fiche dossier
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Dossier d’analyse critique</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programmesLabel() }}</p>
    @php
        $item->loadMissing('analyste');
    @endphp
    <p class="small text-muted mb-0 mt-2">
        <strong>Analyse critique faite par {{ $item->analyste?->name ?? 'l’analyste financier (non renseigné)' }}</strong> — rubriques alignées sur la fiche dossier.
    </p>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.dossier-analyse-critique-document', ['doc' => $doc])
</div>
@endsection

