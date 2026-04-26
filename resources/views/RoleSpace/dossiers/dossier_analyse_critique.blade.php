@extends(match ($space['route'] ?? '') {
    'respexp' => 'Layouts.respexp',
    'juridique' => 'Layouts.juridique',
    'analyste-juridique' => 'Layouts.analyste-juridique',
    'reng' => 'Layouts.reng',
    'analyste-credit' => 'Layouts.analyste-credit',
    'analyste-risques' => 'Layouts.analyste-risques',
    'rerx' => 'Layouts.rerx',
    'dg' => 'Layouts.dg',
    'dga' => 'Layouts.dga',
    default => 'Layouts.app',
})

@section('title', 'Dossier d’analyse critique — '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route(in_array($space['route'] ?? '', ['dg', 'dga'], true) ? $space['route'].'.dossiers.valides-chef-agence' : $space['route'].'.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dossiers.show', $item->token) }}">{{ Str::limit($item->programme?->name ?? 'Dossier', 42) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dossier d’analyse critique</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route($space['route'].'.dossiers.dossier-analyse-critique.pdf', $item->token) }}" class="dropdown-item" target="_blank" rel="noopener">
                <i class="demo-psi-download me-1"></i> Exporter en PDF
            </a>
        </li>
        <li>
            <a href="{{ route($space['route'].'.dossiers.instruction', $item->token) }}" class="dropdown-item">Contenu d’instruction</a>
        </li>
        <li>
            <a href="{{ route($space['route'].'.dossiers.analyse-critique', $item->token) }}" class="dropdown-item">Grille de saisie analyste</a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a href="{{ route($space['route'].'.dossiers.show', $item->token) }}" class="dropdown-item">
                <i class="demo-pli-arrow-left me-1"></i> Retour au dossier
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Dossier d’analyse critique</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programme?->name ?? '—' }}</p>
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
            @foreach($entries as $idx => $entry)
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
