@extends('Layouts.chef_filiere')

@section('title', 'Pièces exigibles — '.$item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.qualifications.show', $item->token) }}">Qualification</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pièces exigibles</li>
    </ol>
</nav>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Pièces exigibles</h5>
    <p class="text-body-secondary mb-0 mt-1 small">{{ $item->name }} — documents de l’entrée en relation.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    <a href="{{ route('chef-filiere.qualifications.show', $item->token) }}" class="cf-back-link">
        <i class="demo-pli-arrow-left" aria-hidden="true"></i> Retour à la qualification
    </a>

    <div class="cf-hero mb-4">
        <h1 class="cf-hero__title">Documents attendus</h1>
        <p class="cf-hero__lead">Contrôlez le statut, consultez les fichiers fournis ou téléversez une pièce.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button></div>
    @endif

    <div class="cf-panel">
        <div class="cf-panel__toolbar">
            <p class="cf-panel__toolbar-label mb-0">Liste des pièces</p>
        </div>
        <div class="cf-table-wrap">
            <table class="table cf-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th scope="col">Pièce</th>
                        <th scope="col">Statut</th>
                        <th scope="col">Document</th>
                        <th scope="col">Téléversement</th>
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
                                <div class="fw-semibold text-dark">{{ $definition->label }}</div>
                                <small class="text-muted">{{ $definition->description }}</small>
                            </td>
                            <td><span class="badge rounded-pill {{ $row['fourni'] ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $row['fourni'] ? 'Fourni' : 'Manquant' }}</span></td>
                            <td>
                                @if($entreprisePiece?->fichier?->path)
                                    <a href="{{ $entreprisePiece->fichier->path }}" target="_blank" class="btn btn-sm btn-outline-primary">Consulter</a>
                                @else
                                    <span class="text-muted small">Aucun fichier</span>
                                @endif
                            </td>
                            <td style="min-width: 280px;">
                                <form method="post" action="{{ route('chef-filiere.entreprises.pieces-exigibles.store', [$item->token, $definition->id]) }}" enctype="multipart/form-data" class="d-flex flex-column flex-xl-row gap-2 align-items-stretch align-items-xl-center">
                                    @csrf
                                    <input type="file" name="fichier" class="form-control form-control-sm" required>
                                    <input type="text" name="notes" class="form-control form-control-sm" placeholder="Notes" value="{{ $entreprisePiece?->notes }}">
                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">Envoyer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="cf-empty border-0">
                                <i class="cf-empty__icon demo-psi-file-word" aria-hidden="true"></i>
                                Aucune pièce exigée paramétrée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
