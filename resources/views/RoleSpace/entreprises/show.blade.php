@extends('Layouts.app')

@section('title', $entreprise->name.' - '.$space['title'])

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $entreprise->name }}</h1>
        <p class="text-muted mb-0">Consultation du portefeuille entreprise.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route($space['route'].'.entreprises.index') }}" class="btn btn-sm btn-outline-secondary">Retour aux entreprises</a>
            <a href="{{ route($space['route'].'.entreprises.pieces', $entreprise->token) }}" class="btn btn-sm btn-outline-primary">Voir les pièces</a>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Statut</small><strong>{{ $entreprise->prospect ? 'Prospect' : 'Client' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Gestionnaire</small><strong>{{ $entreprise->user?->name ?? '—' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Agence</small><strong>{{ $entreprise->agence?->name ?? '—' }}</strong></div></div></div>
            <div class="col-md-3"><div class="card shadow-sm border-0"><div class="card-body"><small class="text-muted d-block">Dossiers</small><strong>{{ $entreprise->dossiers->count() }}</strong></div></div></div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-transparent"><strong>Dossiers rattachés</strong></div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Programme</th>
                                <th>Analyste</th>
                                <th>Gestionnaire</th>
                                <th>État</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entreprise->dossiers as $dossier)
                                <tr>
                                    <td>{{ $dossier->programme?->name ?? '—' }}</td>
                                    <td>{{ $dossier->analyste?->name ?? '—' }}</td>
                                    <td>{{ $dossier->gestionnaire?->name ?? '—' }}</td>
                                    <td>{{ $dossier->status['name'] ?? '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route($space['route'].'.dossiers.show', $dossier->token) }}" class="btn btn-sm btn-primary">Voir</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Aucun dossier rattaché.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
