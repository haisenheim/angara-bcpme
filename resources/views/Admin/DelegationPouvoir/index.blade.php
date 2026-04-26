@extends('Layouts.admin')

@section('title', 'Délégation de pouvoir (instruction)')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Délégation de pouvoir</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Délégation de pouvoir — clôture dossier d’instruction</h5>
        <p class="text-body-secondary mb-0 mt-1">Pour chaque seuil (total des engagements <strong>sollicités</strong> sur le dossier, en XAF), le <strong>profil</strong> indiqué est seul habilité à valider ou rejeter la clôture. Les règles sont triées par seuil croissant : la première dont le total du dossier est ≤ au seuil s’applique. Au-delà de tous les seuils, ou si aucune règle n’est définie, seuls le <strong>DG</strong> et le <strong>DGA</strong> peuvent clôturer.</p>
    </div>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('admin.delegation-pouvoirs.create') }}" class="dropdown-item"><i class="bi bi-plus-lg me-2"></i>Ajouter une règle</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Seuil max (engagements sollicités, XAF)</th>
                        <th>Profil habilité</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $row)
                        <tr>
                            <td>{{ number_format((float) $row->seuil_engagements_max, 0, ',', ' ') }}</td>
                            <td>{{ $row->profil?->name ?? ('#'.$row->profil_id) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.delegation-pouvoirs.edit', $row) }}" class="btn btn-sm btn-outline-primary">Modifier</a>
                                <form action="{{ route('admin.delegation-pouvoirs.destroy', $row) }}" method="post" class="d-inline" onsubmit="return confirm('Supprimer cette règle ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Aucune règle — seuls le DG et le DGA peuvent valider ou rejeter la clôture des dossiers d’instruction.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
