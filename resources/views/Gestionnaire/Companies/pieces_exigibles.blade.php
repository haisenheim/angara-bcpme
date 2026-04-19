@extends('Layouts.gestionnaire')

@section('title', 'Pieces exigibles - '.$item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.show', $item->token) }}">{{ $item->name }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pieces exigibles</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0 mt-2">Checklist des pieces exigibles</h5>
    <p class="text-body-secondary mb-0">Suivi des documents du dossier d'entree en relation.</p>
</div>
@endsection

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Piece</th>
                    <th>Statut</th>
                    <th>Document</th>
                    <th>Televersement</th>
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
                        <td><span class="badge bg-{{ $row['fourni'] ? 'success' : 'secondary' }}">{{ $row['fourni'] ? 'Fourni' : 'Manquant' }}</span></td>
                        <td>
                            @if($entreprisePiece?->fichier?->path)
                                <a href="{{ $entreprisePiece->fichier->path }}" target="_blank" class="btn btn-sm btn-outline-primary">Consulter</a>
                            @else
                                <span class="text-muted">Aucun fichier</span>
                            @endif
                        </td>
                        <td style="min-width:320px;">
                            <form method="post" action="{{ route('gestionnaire.entreprises.pieces-exigibles.store', [$item->token, $definition->id]) }}" enctype="multipart/form-data" class="d-flex gap-2 align-items-center">
                                @csrf
                                <input type="file" name="fichier" class="form-control form-control-sm" required>
                                <input type="text" name="notes" class="form-control form-control-sm" placeholder="Notes" value="{{ $entreprisePiece?->notes }}">
                                <button type="submit" class="btn btn-sm btn-primary">Envoyer</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">Aucune piece exigee parametree.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
