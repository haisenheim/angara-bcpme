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
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dossiers.show', $item->token) }}">{{ Str::limit($item->programmesLabel() ?: 'Dossier', 42) }}</a></li>
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
    <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programmesLabel() ?: '—' }}</p>
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

