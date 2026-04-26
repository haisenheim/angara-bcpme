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
    <p class="small text-muted mb-0 mt-2">Synthèse chronologique des avis et contenus associés au dossier d’instruction (du plus ancien au plus récent).</p>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.dossier-pieces-jointes', [
        'dossier' => $item,
        'showUpload' => false,
    ])
    @if($entries->isEmpty())
        <div class="alert alert-light border">
            <p class="mb-0">Aucun avis ou contenu structuré n’a encore été enregistré pour ce dossier dans la chronologie d’instruction.</p>
        </div>
    @else
        <div class="timeline-ac list-group list-group-flush">
            @foreach($entries as $entry)
                <div class="list-group-item px-0 py-4 border-bottom rich-text-rendered">
                    <div class="d-flex flex-wrap justify-content-between gap-2 align-items-start mb-2">
                        <div>
                            <span class="badge bg-secondary me-2">{{ $entry['at']->format('d/m/Y H:i') }}</span>
                            <strong>{{ $entry['label'] }}</strong>
                        </div>
                    </div>
                    <div class="small text-muted mb-2">
                        <strong>{{ $entry['author_name'] }}</strong>
                        <span class="mx-1">·</span>
                        <span>{{ $entry['author_profile'] }}</span>
                        @if(($entry['origin'] ?? '') === 'analyse_critique')
                            <span class="badge bg-light text-dark border ms-2">Analyse critique (entreprise)</span>
                        @endif
                    </div>
                    <div class="border rounded p-3 bg-light small">
                        {!! $entry['body_html'] !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
