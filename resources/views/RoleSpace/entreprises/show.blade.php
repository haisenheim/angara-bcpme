@extends(match ($space['route'] ?? '') {
    'gestionnaire' => 'Layouts.gestionnaire',
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

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
@endpush

@include('partials.entreprise-fiche-styles')

@php
    $entityListLabel = in_array($space['route'] ?? '', ['dg', 'dga'], true) ? 'Clients' : 'Entreprises';
    $isAnalysteCreditSpace = (($space['route'] ?? '') === 'analyste-credit')
        || str_starts_with((string) (request()->route()?->getName() ?? ''), 'analyste-credit.');
    $engagementsReadRoute = ($space['route'] ?? '').'.entreprises.engagements';
    $hasEngagementsReadRoute = \Illuminate\Support\Facades\Route::has($engagementsReadRoute);
@endphp

@section('title', $item->name.' - '.$space['title'])

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.index') }}">{{ $entityListLabel }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    @if(($space['route'] ?? '') === 'gestionnaire')
        <x-page-actions-dropdown button-id="gestionnaireEntrepriseShowActions">
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.fiche.pdf', $item->token) }}" target="_blank" rel="noopener"><i class="bi bi-printer me-2"></i>Imprimer la fiche (PDF)</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" data-bs-target="#addAppuiModal" data-bs-toggle="modal" href="#"><i class="demo-psi-add me-2"></i>Ajouter un appui</a></li>
            <li><a class="dropdown-item" data-bs-target="#addElementModal" data-bs-toggle="modal" href="#"><i class="demo-psi-file me-2"></i>Ajouter une pièce</a></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.pieces-exigibles.index', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Checklist pièces exigibles</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.questionnaire', $item->token) }}"><i class="demo-psi-file-edit me-2"></i>Questionnaire de mise en relation</a></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.physique.create', $item->token) }}"><i class="demo-psi-male me-2"></i>Tiers personne physique</a></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.morale.create', $item->token) }}"><i class="demo-psi-building me-2"></i>Tiers personne morale</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.edit', $item->token) }}"><i class="demo-psi-pen-5 me-2"></i>Completer la fiche</a></li>
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Grille des engagements</a></li>
            @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
            <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.analyse-critique.show', $item->token) }}"><i class="demo-psi-file-edit me-2"></i>Dossier d'analyse critique</a></li>
        </x-page-actions-dropdown>
    @else
        <x-page-actions-dropdown button-id="roleSpaceEntrepriseShowActions">
            @php
                $fichePdfRoute = ($space['route'] ?? '').'.entreprises.fiche.pdf';
                $hasFichePdfRoute = \Illuminate\Support\Facades\Route::has($fichePdfRoute);
            @endphp
            @if($hasFichePdfRoute)
                <li><a class="dropdown-item" href="{{ route($fichePdfRoute, $item->token) }}" target="_blank" rel="noopener"><i class="bi bi-printer me-2"></i>Imprimer la fiche (PDF)</a></li>
                <li><hr class="dropdown-divider"></li>
            @endif
            @if($isAnalysteCreditSpace)
                <li><a class="dropdown-item" href="{{ route('analyste-credit.entreprise.get.engagements', $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Grille des engagements du client</a></li>
                @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
                <li><hr class="dropdown-divider"></li>
            @elseif($hasEngagementsReadRoute)
                <li><a class="dropdown-item" href="{{ route($engagementsReadRoute, $item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Grille des engagements du client</a></li>
                @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
                <li><hr class="dropdown-divider"></li>
            @else
                @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
            @endif
            <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.index') }}">Retour liste entreprises</a></li>
            <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.pieces', $item->token) }}">Pièces exigibles</a></li>
        </x-page-actions-dropdown>
    @endif
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
        <x-statut-badge :statut="$item->clientStatutPresentation()" :show-detail="false" />
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
    'sitesEquipeCanCrud' => (($space['route'] ?? '') === 'gestionnaire'),
])

@if(($space['route'] ?? '') === 'gestionnaire')
    <div class="modal fade" id="addAppuiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un appui</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.entreprise.appui.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <div class="">
                            <div class="form-group">
                                <label class="form-label">Appui</label>
                                <select required name="appui_id" id="appui_id" class="form-select">
                                    <option value="0">Selectionner un appui ...</option>
                                    @foreach(($appuis ?? []) as $it)
                                        <option value="{{ $it->id }}">{{ $it->name }} ({{ $it->financier?'financier':'non financier' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-5">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addElementModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une pièce constitutive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('gestionnaire.entreprise.element.save') }}" method="post">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <div class="mb-3">
                            <label for="type_id" class="form-label">Type de pièce</label>
                            <select required name="type_id" id="type_id" class="form-select">
                                <option value="0">Selectionner un type de pièce  ...</option>
                                @foreach(($elements ?? []) as $it)
                                    <option value="{{ $it->id }}">{{ $it->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fichier" class="form-label">Fichier</label>
                            <input type="file" name="fichier" id="fichier" class="form-control">
                        </div>
                        <div class="mt-5 d-grid">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-group{
            margin-top: 1rem;
        }
    </style>
@endif
@endsection
