@extends('Layouts.app')

@section('title', 'Dossier - '.$space['title'])

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Dossier {{ $dossier->programme?->name ?? '' }}</h1>
        <p class="text-muted mb-0">{{ $dossier->entreprise?->name ?? 'Entreprise non renseignée' }}</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route($space['route'].'.dossiers.index') }}" class="btn btn-sm btn-outline-secondary">Retour aux dossiers</a>
            @if($dossier->entreprise)
                <a href="{{ route($space['route'].'.entreprises.show', $dossier->entreprise->token) }}" class="btn btn-sm btn-outline-primary">Voir l'entreprise</a>
            @endif
        </div>

        <div class="row g-3">
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Programme</small><strong>{{ $dossier->programme?->name ?? '—' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Analyste</small><strong>{{ $dossier->analyste?->name ?? '—' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Gestionnaire</small><strong>{{ $dossier->gestionnaire?->name ?? '—' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">État</small><strong>{{ $dossier->status['name'] ?? '—' }}</strong></div></div></div>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent"><strong>Indicateurs et réponses</strong></div>
                    <div class="card-body">
                        <p class="mb-2">Indicateurs financiers : <strong>{{ $dossier->indicateurs->count() }}</strong></p>
                        <p class="mb-0">Réponses d'instruction : <strong>{{ $dossier->reponses->count() }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-transparent"><strong>Contexte entreprise</strong></div>
                    <div class="card-body">
                        <p class="mb-2">Entreprise : <strong>{{ $dossier->entreprise?->name ?? '—' }}</strong></p>
                        <p class="mb-2">Agence : <strong>{{ $dossier->agence?->name ?? '—' }}</strong></p>
                        <p class="mb-0">Note calculée : <strong>{{ number_format((float) ($dossier->note ?? 0), 2, ',', ' ') }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
