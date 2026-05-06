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

@section('title', 'Analyse critique analyste — '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route(in_array($space['route'] ?? '', ['dg', 'dga'], true) ? $space['route'].'.dossiers.valides-chef-agence' : $space['route'].'.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dossiers.show', $item->token) }}">{{ Str::limit($item->programme?->name ?? 'Dossier', 42) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Sept zones — analyse critique</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route($space['route'].'.dossiers.dossier-analyse-critique', $item->token) }}" class="dropdown-item">Dossier d’analyse critique (PDF / export)</a>
        </li>
        <li>
            <a href="{{ route($space['route'].'.dossiers.instruction', $item->token) }}" class="dropdown-item">Contenu d’instruction (grille détaillée)</a>
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
    <h5 class="page-title mb-0">Analyse critique — sept zones analyste (lecture seule)</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programmesLabel() ?: '—' }}</p>
    <p class="small text-muted mb-0 mt-2">Les rubriques ci-dessous alimentent l’<strong>avis du chargé d’instruction</strong> ; le <strong>dossier d’analyse critique</strong> reprend ce même contenu (document dédié).</p>
</div>
@endsection

@section('content')
<div class="container-fluid">
    @include('partials.dossier-pieces-jointes', [
        'dossier' => $item,
        'showUpload' => false,
    ])
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-4">
                    <h4 class="text-center mb-0">SEPT ZONES DE L’ANALYSE CRITIQUE</h4>
                </div>
                <div class="card-body">
                    <div class="mt-1 border rounded rounded-2 p-3">
                        <h4 class="fs-5">1. INFORMATIONS GENERALES</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->donnees_generales ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h4 class="fs-5">2. ANALYSE D'ENSEMBLE</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->analyse_ensemble ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h4 class="fs-5">3. ANALYSE FINANCIERE</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->analyse_financiere ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h4 class="fs-5">4. APPUIS FINANCIERS ET NON FINANCIERS</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->appuis ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h4 class="fs-5">5. ANALYSE DU RISQUE ET DE LA CAPACITE DE REMBOURSEMENT</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->analyse_risque ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h4 class="fs-5">6. RENTABILITE DE LA RELATION POUR L'ETABILISSEMENT</h4>
                        <div class="lh-base rich-text-rendered small">{!! $item->analyse_rentabilite ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3">
                        <h3 class="fs-5">7. CONCLUSIONS GENERALES POUR L'ANALYSTE</h3>
                        <div class="lh-base rich-text-rendered small">{!! $item->conclusions_analyste ?? '—' !!}</div>
                    </div>
                    <div class="mt-4 border rounded rounded-2 p-3 border-primary border-2">
                        <h4 class="fs-5">Complément — chef d’agence</h4>
                        <p class="small text-muted mb-2">Hors des sept zones analyste ; avis ou conclusions du chef d’agence sur le dossier d’instruction.</p>
                        <div class="lh-base rich-text-rendered small">{!! $item->conclusions_ca ?? '—' !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
