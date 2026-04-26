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

@section('title', 'Instruction — '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route(in_array($space['route'] ?? '', ['dg', 'dga'], true) ? $space['route'].'.dossiers.valides-chef-agence' : $space['route'].'.dossiers.index') }}">Dossiers</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dossiers.show', $item->token) }}">{{ Str::limit($item->programmesLabel() ?: 'Dossier', 42) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Contenu d’instruction</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route($space['route'].'.dossiers.dossier-analyse-critique', $item->token) }}" class="dropdown-item">Dossier d’analyse critique</a>
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
    <h5 class="page-title mb-0">Travail d’instruction (lecture seule)</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $item->entreprise?->name ?? '—' }} — {{ $item->programmesLabel() ?: '—' }}</p>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="alert alert-light border mb-4">
        @if(($space['route'] ?? '') === 'juridique')
            <strong>Consultation réservée au pôle juridique</strong> — contenu transmis par l’analyste financier après validation exploitation.
        @else
            <strong>Consultation réservée au responsable exploitation</strong> après transmission du dossier par l’analyste financier ou par le chef d’agence.
        @endif
        Aucune modification n’est possible depuis cet écran.
    </div>

    @include('partials.instruction-dossier-consultation', ['dossier' => $item, 'instructionConsultation' => $instructionConsultation ?? null])

    @include('partials.dossier-pieces-jointes', [
        'dossier' => $item,
        'showUpload' => false,
    ])

    @if($item->isInstructionSubmittedToExploitation() && $item->exploitation_analyste_instruction_avis)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-text me-2 text-primary"></i>Avis du chargé d’instruction</h6>
                <p class="small text-muted mb-0 mt-1">Tel que transmis au responsable exploitation avec le dossier.</p>
            </div>
            <div class="card-body pt-0">
                <div class="rich-text-rendered small border rounded p-3 bg-light">{!! $item->exploitation_analyste_instruction_avis !!}</div>
            </div>
        </div>
    @endif
    @if($item->isInstructionCaTransmittedToExploitation() && strlen(trim(strip_tags((string) ($item->instruction_agence_ca_avis ?? '')))) > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 py-3">
                <h6 class="mb-0 fw-semibold"><i class="demo-psi-file-text me-2 text-primary"></i>Avis du chef d’agence</h6>
                <p class="small text-muted mb-0 mt-1">Transmis au responsable exploitation avec le dossier.</p>
            </div>
            <div class="card-body pt-0">
                <div class="rich-text-rendered small border rounded p-3 bg-light">{!! $item->instruction_agence_ca_avis !!}</div>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-3">
                    <h6 class="mb-0 fw-semibold"><i class="demo-psi-information me-2 text-primary"></i> Données financières &amp; PME</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Import DSF et saisies effectués par l’analyste dans son espace.</p>
                    <p class="mb-2 small">Indicateurs financiers enregistrés : <strong>{{ $indicateurs->count() }}</strong></p>
                    @if($sme)
                        <div class="border rounded p-3 bg-light mt-2">
                            <h6 class="fw-semibold mb-1">{{ $sme->name ?? $sme['name'] ?? '—' }}</h6>
                            @if(isset($sme->mention) || isset($sme['mention']))
                                <p class="text-muted small mb-2">{{ $sme->mention ?? $sme['mention'] }}</p>
                            @endif
                        </div>
                    @endif
                    @if($item->entreprise?->token)
                        <a href="{{ route($space['route'].'.entreprises.show', $item->entreprise->token) }}" class="btn btn-sm btn-outline-secondary mt-3">Fiche entreprise</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            @include('RoleSpace.dossiers.partials.instruction_grille_notation', [
                'item' => $item,
                'criteres' => $criteres,
                'indicateurs' => $indicateurs,
                'sme' => $sme,
                'readOnly' => true,
            ])
        </div>
    </div>
</div>
@endsection

@section('script')
<style>
.table-notation th { font-weight: 600; }
.table-notation .vertical-align { vertical-align: middle !important; text-align: center; }
</style>
@endsection
