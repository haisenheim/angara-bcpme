@extends('Layouts.admin')

@section('title', 'Frameworks d\'évaluation')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Paramétrage ESG - Frameworks</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('admin.evaluation-frameworks.create') }}" class="btn btn-primary btn-sm">Nouveau</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Frameworks d'évaluation</h5>
        <p class="lead mb-0">Référentiels d'évaluation ESG</p>
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
                    <tr><th>Nom</th><th>Code</th><th>Actif</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($frameworks as $f)
                        <tr>
                            <td>{{ $f->name }}</td>
                            <td>{{ $f->code }}</td>
                            <td>{{ $f->is_active ? 'Oui' : 'Non' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="demo-psi-dot-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-frameworks.show', $f) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-frameworks.edit', $f) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.evaluation-frameworks.destroy', $f) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce framework ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="demo-psi-trash me-2"></i> Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Aucun framework.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $frameworks->links() }}
        </div>
    </div>
@endsection
