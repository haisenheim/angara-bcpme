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

@php
    $entityListLabel = in_array($space['route'] ?? '', ['dg', 'dga'], true) ? 'Clients' : 'Entreprises';
@endphp

@section('title', 'Pièces - '.$entreprise->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.index') }}">{{ $entityListLabel }}</a></li>
        <li class="breadcrumb-item"><a href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}">{{ Str::limit($entreprise->name, 32) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pièces exigibles</li>
    </ol>
</nav>
@endsection

@section('actions')
<x-page-actions-dropdown button-id="roleSpaceEntreprisePiecesActions">
    <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.show', $entreprise->token) }}"><i class="demo-pli-arrow-left me-2"></i>Retour à l'entreprise</a></li>
    <li><a class="dropdown-item" href="{{ route($space['route'].'.entreprises.index') }}"><i class="demo-pli-building me-2"></i>Liste des {{ strtolower($entityListLabel) }}</a></li>
</x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Pièces exigibles</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $entreprise->name }} — checklist en lecture seule</p>
</div>
@endsection

@section('content')
    <div class="container-fluid">
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
