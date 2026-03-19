@extends('Layouts.admin')

@section('title', 'Seuils de scoring')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Paramétrage ESG - Seuils</li>
    </ol>
</nav>
@endsection
@section('actions')
    <a href="{{ route('admin.evaluation-thresholds.create') }}" class="btn btn-primary btn-sm">Nouveau</a>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Seuils de scoring</h5>
        <p class="lead mb-0">Niveaux de risque, bancabilité et éligibilité</p>
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
                    <tr><th>Nom</th><th>Type</th><th>Label</th><th>Min</th><th>Max</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($thresholds as $t)
                        <tr>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->threshold_type }}</td>
                            <td>{{ $t->label }}</td>
                            <td>{{ $t->min_value }}</td>
                            <td>{{ $t->max_value }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="demo-psi-dot-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-thresholds.show', $t) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-thresholds.edit', $t) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.evaluation-thresholds.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce seuil ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="demo-psi-trash me-2"></i> Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Aucun seuil.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $thresholds->links() }}
        </div>
    </div>
@endsection
