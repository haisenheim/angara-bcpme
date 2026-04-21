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

@section('title', $space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Espace {{ $space['title'] }}</h5>
        <p class="text-muted mb-0">
            @if($space['route'] === 'respexp')
                Dossiers sans analyste à affecter (périmètre banque) à un utilisateur du profil instruction (id 17), puis instruction par l’analyste.
            @elseif($space['route'] === 'reng')
                Dossiers transmis au pôle engagements après le pôle juridique — affectation analyste crédit et transmission risques.
            @elseif($space['route'] === 'analyste-credit')
                Contre-analyse et avis sur les dossiers du pôle engagements qui vous sont affectés.
            @elseif($space['route'] === 'rerx')
                Dossiers reçus du responsable engagements pour le pôle risques — affectation analyste risques et transmission direction.
            @elseif($space['route'] === 'analyste-risques')
                Dossiers du pôle risques qui vous sont affectés en tant qu’analyste risques.
            @else
                Consultation transverse des clients, dossiers et pièces du portefeuille.
            @endif
        </p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Entreprises</small>
                        <div class="fs-4 fw-semibold">{{ $stats['entreprises'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Dossiers</small>
                        <div class="fs-4 fw-semibold">{{ $stats['dossiers'] }}</div>
                    </div>
                </div>
            </div>
            @if(($space['route'] ?? '') === 'respexp' && isset($stats['dossiers_a_affecter']))
            <div class="col-md-4">
                <div class="card shadow-sm border-0 border-warning">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">À affecter (sans analyste)</small>
                        <div class="fs-4 fw-semibold text-warning-emphasis">{{ $stats['dossiers_a_affecter'] }}</div>
                        <a href="{{ route('respexp.dossiers.index') }}" class="small">Voir la liste</a>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Pièces fournies</small>
                        <div class="fs-4 fw-semibold">{{ $stats['pieces'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-wrap gap-2">
                <a href="{{ route($space['route'].'.entreprises.index') }}" class="btn btn-primary">Voir les entreprises</a>
                <a href="{{ route($space['route'].'.dossiers.index') }}" class="btn btn-outline-primary">Voir les dossiers</a>
            </div>
        </div>
    </div>
@endsection
