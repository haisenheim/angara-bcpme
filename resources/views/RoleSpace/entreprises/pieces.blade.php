@extends('Layouts.app')

@section('title', 'Pièces - '.$entreprise->name)

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Pièces de {{ $entreprise->name }}</h1>
        <p class="text-muted mb-0">Checklist documentaire en lecture seule.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}" class="btn btn-sm btn-outline-secondary">Retour à l'entreprise</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Pièce</th>
                            <th>Statut</th>
                            <th>Document</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checklist as $row)
                            @php
                                $definition = $row['definition'];
                                $entreprisePiece = $row['entreprise_piece'];
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $definition->label }}</div>
                                    <small class="text-muted">{{ $definition->description }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $row['fourni'] ? 'success' : 'secondary' }}">
                                        {{ $row['fourni'] ? 'Fourni' : 'Manquant' }}
                                    </span>
                                </td>
                                <td>
                                    @if($entreprisePiece?->fichier?->path)
                                        <a href="{{ $entreprisePiece->fichier->path }}" target="_blank" class="btn btn-sm btn-outline-primary">Consulter</a>
                                    @else
                                        <span class="text-muted">Aucun fichier</span>
                                    @endif
                                </td>
                                <td>{{ $entreprisePiece?->notes ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">Aucune pièce exigible paramétrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
