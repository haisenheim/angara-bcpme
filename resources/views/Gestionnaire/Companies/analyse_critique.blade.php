@extends('Layouts.gestionnaire')

@section('title', 'Analyse critique - '.$item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ $item->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Analyse critique</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0 mt-2">Dossier d'analyse critique</h5>
    <p class="text-body-secondary mb-0">Consolidation des avis sur le prospect / client, l'EER et les dossiers d'instruction.</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>Synthese</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('gestionnaire.entreprises.analyse-critique.update', $item->token) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Statut</label>
                        <input type="text" class="form-control" name="statut" value="{{ $analyseCritique?->statut }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Synthese consolidée</label>
                        <textarea name="synthese" class="form-control" rows="14">{{ old('synthese', $analyseCritique?->synthese) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer la synthese</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <strong>Avis consolidés</strong>
            </div>
            <div class="card-body">
                @forelse($analyseCritique?->avis ?? [] as $avis)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between gap-3 flex-wrap">
                            <div>
                                <div class="fw-semibold">{{ $avis->source_label ?? $avis->source_type }}</div>
                                <div class="small text-muted">{{ $avis->source_type }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary">{{ $avis->etat }}</span>
                                <div class="small text-muted mt-1">{{ optional($avis->emis_at)->format('d/m/Y H:i') ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="small text-muted mt-2 mb-2">{{ $avis->emisPar?->name ?? 'Systeme' }}</div>
                        <div style="white-space: pre-wrap;">{{ $avis->contenu }}</div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Aucun avis n'a encore ete consolide.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
