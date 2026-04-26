@extends('Layouts.app')

@section('title', 'Mon profil')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mon profil</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div class="d-flex flex-column flex-md-row align-items-md-end justify-content-md-between gap-3">
    <div>
        <h1 class="h4 page-title mb-1">Mon profil</h1>
        <p class="text-muted mb-0 small">Mettez à jour vos informations d’affichage et, si besoin, votre mot de passe.</p>
    </div>
</div>
@endsection

@section('content')
@php
    $roleLabel = optional($user->role)->name;
    $agenceLabel = optional($user->agence)->name;
    $entiteLabel = optional($user->organisationEntite)->name;
@endphp

<div class="row justify-content-center">
    <div class="col-12 col-xl-10 col-xxl-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row g-4 g-lg-5">
                    <div class="col-lg-4 text-lg-center">
                        <div class="d-inline-block position-relative">
                            <img
                                src="{{ $user->photo }}"
                                alt="Photo de profil"
                                width="112"
                                height="112"
                                class="rounded-circle border bg-light object-fit-cover"
                                style="width: 112px; height: 112px;"
                            >
                        </div>
                        <div class="mt-3 text-lg-center">
                            <div class="fw-semibold">{{ $user->name }}</div>
                            @if ($roleLabel)
                                <div class="text-muted small mt-1">{{ $roleLabel }}</div>
                            @endif
                            @if ($agenceLabel)
                                <div class="text-muted small">{{ $agenceLabel }}</div>
                            @elseif ($entiteLabel)
                                <div class="text-muted small">{{ $entiteLabel }}</div>
                            @endif
                        </div>
                        <hr class="d-none d-lg-block my-4">
                        <dl class="small text-muted mb-0 d-none d-lg-block text-start">
                            <dt class="fw-normal text-body-secondary mb-1">Compte</dt>
                            <dd class="mb-0 text-break">{{ $user->email }}</dd>
                        </dl>
                    </div>

                    <div class="col-lg-8">
                        <form action="{{ route('profile.store') }}" method="post" enctype="multipart/form-data" novalidate>
                            @csrf

                            <h2 class="h6 text-body-secondary text-uppercase small mb-3">Coordonnées</h2>

                            <div class="mb-3">
                                <label for="profile-name" class="form-label">Nom affiché</label>
                                <input
                                    id="profile-name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    autocomplete="name"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="profile-email" class="form-label">Adresse e-mail</label>
                                <input
                                    id="profile-email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror"
                                    autocomplete="email"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <h2 class="h6 text-body-secondary text-uppercase small mb-3">Photo</h2>

                            <div class="mb-4">
                                <label for="profile-photo" class="form-label">Remplacer la photo</label>
                                <input
                                    id="profile-photo"
                                    type="file"
                                    name="photo"
                                    accept="image/jpeg,image/png,image/gif,.jpg,.jpeg,.png,.gif"
                                    class="form-control @error('photo') is-invalid @enderror"
                                >
                                <div class="form-text">JPEG, PNG ou GIF — 4 Mo maximum. Laisser vide pour conserver la photo actuelle.</div>
                                @error('photo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <h2 class="h6 text-body-secondary text-uppercase small mb-3">Mot de passe</h2>

                            <p class="text-muted small mb-3">Ne remplissez ces champs que si vous souhaitez changer votre mot de passe.</p>

                            <div class="mb-3">
                                <label for="profile-password" class="form-label">Nouveau mot de passe</label>
                                <input
                                    id="profile-password"
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    autocomplete="new-password"
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="profile-password-confirmation" class="form-label">Confirmation</label>
                                <input
                                    id="profile-password-confirmation"
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    autocomplete="new-password"
                                >
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2 pt-2 border-top">
                                <button type="submit" class="btn btn-primary px-4">Enregistrer</button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary">Retour</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
