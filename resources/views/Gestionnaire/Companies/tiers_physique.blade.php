@extends('Layouts.gestionnaire')

@section('title', 'Nouveau tiers personne physique - ' . Str::limit($item->name, 30))
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ Str::limit($item->name, 30) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouveau tiers personne physique</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="dropdown-item">
                <i class="demo-psi-arrow-left me-2"></i>Annuler
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Nouveau tiers personne physique</h5>
        <p class="text-body-secondary mb-0 mt-1">Ajouter un tiers pour l'entreprise <strong>{{ $item->name }}</strong></p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="demo-psi-male fs-3 text-primary me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-semibold">Entreprise concernée</h6>
                            <span class="text-danger fw-medium">{{ $item->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ route('gestionnaire.entreprise.physique.save') }}">
                        @csrf
                        <input type="hidden" name="entreprise_id" value="{{ $item->id }}">
                        <input type="hidden" name="token" value="{{ $item->token }}">

                        {{-- Identification --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="demo-psi-id me-1"></i>Identification
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Nom et prénom</label>
                                    <input required type="text" id="name" name="name" placeholder="Nom et prénom du tiers" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="niu" class="form-label">N° d'identifiant unique</label>
                                    <input required type="text" id="niu" name="niu" placeholder="NIU / Impôt" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Contact --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="demo-psi-mail me-1"></i>Contact
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input required type="email" id="email" name="email" placeholder="Adresse email" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input required type="text" id="phone" name="phone" placeholder="Numéro de téléphone" class="form-control">
                                </div>
                            </div>
                        </div>

                        {{-- Adresse --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="demo-psi-map me-1"></i>Adresse / domicile
                            </h6>
                            <div>
                                <label for="address" class="form-label">Adresse physique</label>
                                <textarea required name="address" id="address" class="form-control" rows="3" placeholder="Adresse complète du domicile"></textarea>
                            </div>
                        </div>

                        {{-- Lien avec le dirigeant --}}
                        <div class="mb-4">
                            <h6 class="text-uppercase text-muted small fw-semibold mb-3">
                                <i class="demo-psi-handshake me-1"></i>Lien avec le dirigeant principal
                            </h6>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input id="lien_familial" class="form-check-input" type="radio" name="lien" value="Familial" checked>
                                    <label for="lien_familial" class="form-check-label">Familial</label>
                                </div>
                                <div class="form-check">
                                    <input id="lien_professionnel" class="form-check-input" type="radio" name="lien" value="Professionnel">
                                    <label for="lien_professionnel" class="form-check-label">Professionnel</label>
                                </div>
                                <div class="form-check">
                                    <input id="lien_associatif" class="form-check-input" type="radio" name="lien" value="Associatif">
                                    <label for="lien_associatif" class="form-check-label">Associatif</label>
                                </div>
                                <div class="form-check">
                                    <input id="lien_amical" class="form-check-input" type="radio" name="lien" value="Amical">
                                    <label for="lien_amical" class="form-check-label">Amical</label>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('gestionnaire.entreprises.show', $item->token) }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="demo-psi-check me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
