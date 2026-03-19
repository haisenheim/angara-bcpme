@extends('Layouts.gestionnaire')

@section('title', 'Profil ESG - ' . $entreprise->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.evaluation-profiles.index') }}">Profils ESG</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $entreprise->name }}</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entreprises.evaluation-profile.edit', $entreprise) }}" class="btn btn-primary btn-sm">Modifier</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Profil d'évaluation ESG - {{ $entreprise->name }}</h5>
        <p class="lead">Informations ESG structurelles de l'entreprise</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(!$profile)
        <div class="alert alert-info">Aucun profil créé. <a href="{{ route('gestionnaire.entreprises.evaluation-profile.create', $entreprise) }}">Créer le profil</a></div>
    @else
        <div class="card">
            <div class="card-header"><strong>Informations générales</strong></div>
            <div class="card-body">
                <p><strong>Secteur :</strong> {{ $profile->business_sector ?? '-' }}</p>
                <p><strong>Chiffre d'affaires :</strong> {{ $profile->annual_turnover ? number_format($profile->annual_turnover, 0, ',', ' ') : '-' }}</p>
                <p><strong>Effectif total :</strong> {{ $profile->employees_total ?? 0 }}</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><strong>Environnement</strong></div>
            <div class="card-body">
                <p>Politique environnementale : {{ $profile->env_policy ? 'Oui' : 'Non' }}</p>
                <p>Certification : {{ $profile->env_certified ? 'Oui' : 'Non' }}</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><strong>Social</strong></div>
            <div class="card-body">
                <p>Dirigée par des femmes : {{ $profile->women_led ? 'Oui' : 'Non' }}</p>
                <p>Dirigée par des jeunes : {{ $profile->youth_led ? 'Oui' : 'Non' }}</p>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header"><strong>Gouvernance</strong></div>
            <div class="card-body">
                <p>Conseil d'administration : {{ $profile->has_board ? 'Oui' : 'Non' }}</p>
                <p>États financiers audités : {{ $profile->audited_financials ? 'Oui' : 'Non' }}</p>
            </div>
        </div>
        @if($entreprise->dossiers->isNotEmpty())
            <div class="card mt-3">
                <div class="card-header"><strong>Dossier(s) ESG lié(s)</strong></div>
                <div class="card-body">
                    @foreach($entreprise->dossiers as $d)
                        @if($d->esgEvaluation)
                            <p><a href="{{ route('gestionnaire.dossiers.show', $d) }}">{{ $d->programme?->name }}</a> - Score : {{ $d->esgEvaluation->score_global }}/100 - {{ ucfirst($d->esgEvaluation->status) }}</p>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    @endif
@endsection
