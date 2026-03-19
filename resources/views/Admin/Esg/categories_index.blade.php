@extends('Layouts.admin')

@section('title', 'Catégories d\'évaluation')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Paramétrage ESG - Catégories</li>
    </ol>
</nav>
@endsection
@section('actions')
    <a href="{{ route('admin.evaluation-categories.create') }}" class="btn btn-primary btn-sm">Nouveau</a>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Catégories d'évaluation</h5>
        <p class="lead mb-0">Gestion des catégories ESG (Environnement, Social, Gouvernance, etc.)</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr><th>Nom</th><th>Code</th><th>Pondération</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($categories as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->code }}</td>
                            <td>{{ $c->weight }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="demo-psi-dot-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-categories.show', $c) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-categories.edit', $c) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.evaluation-categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="demo-psi-trash me-2"></i> Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Aucune catégorie.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $categories->links() }}
        </div>
    </div>
@endsection
