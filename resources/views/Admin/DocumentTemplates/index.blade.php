@extends('Layouts.admin')

@section('title', 'Modèles de documents')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Administration</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modèles de documents</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown menu-class="dropdown-menu dropdown-menu-end border shadow-sm py-2">
        <li>
            <a href="{{ route('document-templates.index') }}" target="_blank" rel="noopener" class="dropdown-item">
                <i class="bi bi-box-arrow-up-right me-2"></i> Bibliothèque (vue métier)
            </a>
        </li>
        <li>
            <a href="{{ route('admin.document-templates.create') }}" class="dropdown-item"><i class="demo-pli-add me-2 fs-5"></i>Ajouter un fichier</a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Modèles de documents</h5>
        <p class="lead mb-0">Documents téléchargeables par tous les utilisateurs connectés.</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($items->isEmpty())
                <p class="text-muted mb-0">Aucun modèle pour le moment. Ajoutez un fichier depuis le menu Actions.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Fichier d’origine</th>
                                <th class="text-end">Taille</th>
                                <th class="text-center">Ordre</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row->title }}</strong>
                                        @if($row->description)
                                            <div class="small text-muted">{{ Str::limit($row->description, 160) }}</div>
                                        @endif
                                    </td>
                                    <td><span class="text-muted small">{{ $row->original_filename }}</span></td>
                                    <td class="text-end small">
                                        @if($row->size_bytes)
                                            @if($row->size_bytes >= 1048576)
                                                {{ number_format($row->size_bytes / 1048576, 1, ',', ' ') }} Mo
                                            @elseif($row->size_bytes >= 1024)
                                                {{ number_format($row->size_bytes / 1024, 1, ',', ' ') }} Ko
                                            @else
                                                {{ $row->size_bytes }} o
                                            @endif
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $row->sort_order }}</td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('document-templates.download', $row) }}" class="btn btn-xs btn-outline-primary">Télécharger</a>
                                        <form action="{{ route('admin.document-templates.destroy', $row) }}" method="post" class="d-inline" onsubmit="return confirm('Supprimer ce modèle ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-outline-danger">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
