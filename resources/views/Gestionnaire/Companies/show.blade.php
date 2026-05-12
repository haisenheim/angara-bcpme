@extends('Layouts.gestionnaire')

@include('partials.entreprise-fiche-styles')

@section('title', $item->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 40) }}</li>
    </ol>
</nav>
@endsection
@section('actions')
<x-page-actions-dropdown button-id="gestionnaireEntrepriseShowActions">
    <li><a class="dropdown-item" data-bs-target="#addAppuiModal" data-bs-toggle="modal" href="#"><i class="demo-psi-add me-2"></i>Ajouter un appui</a></li>
    <li><a class="dropdown-item" data-bs-target="#addElementModal" data-bs-toggle="modal" href="#"><i class="demo-psi-file me-2"></i>Ajouter une pièce</a></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.pieces-exigibles.index',$item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Checklist pièces exigibles</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.questionnaire',$item->token) }}"><i class="demo-psi-file-edit me-2"></i>Questionnaire de mise en relation</a></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.physique.create',$item->token) }}"><i class="demo-psi-male me-2"></i>Tiers personne physique</a></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.morale.create',$item->token) }}"><i class="demo-psi-building me-2"></i>Tiers personne morale</a></li>
    <li><hr class="dropdown-divider"></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.edit',$item->token) }}"><i class="demo-psi-pen-5 me-2"></i>Completer la fiche</a></li>
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprise.get.engagements',$item->token) }}"><i class="demo-psi-file-text-image me-2"></i>Grille des engagements</a></li>
    @include('partials.entreprise-fiche-li-simulation-credit', ['item' => $item])
    <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.analyse-critique.show',$item->token) }}"><i class="demo-psi-file-edit me-2"></i>Dossier d'analyse critique</a></li>
</x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <h5 class="page-title mb-0">{{ $item->name }}</h5>
        @if($item->prospect)
            <span class="badge bg-danger">Prospect</span>
        @endif
        <span class="badge bg-secondary">{{ $item->taille }}</span>
        <span class="badge bg-{{ $item->caractere === 'Formel' ? 'success' : 'warning' }}">{{ $item->caractere }}</span>
        <x-statut-badge :statut="$item->clientStatutPresentation()" :show-detail="false" />
    </div>
    <p class="text-body-secondary mb-0 mt-1">Dossier entreprise — {{ $item->forme?->name ?? '—' }}</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

@include('partials.entreprise-qualification-chef-filiere', ['item' => $item, 'qualificationContext' => 'gestionnaire'])

@include('partials.entreprise-fiche-client-ca-body', [
    'item' => $item,
    'mr' => $mr,
    'checklist' => $checklist,
    'dossierShowRoute' => 'gestionnaire.dossiers.show',
    'programmeShowRoute' => 'gestionnaire.programmes.show',
    'tiersEntrepriseShowRoute' => 'gestionnaire.entreprises.show',
    'sitesEquipeCanCrud' => true,
])

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
                                        @foreach($appuis as $it)
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
                                        @foreach($elements as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="fichier" class="form-label">Fichier</label>
                                    <input type="file" name="fichier" id="fichier" class="form-control">
                                </div>
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
@endsection
