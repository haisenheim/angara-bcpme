@extends('Layouts.admin')

@section('title', 'Réglages ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Paramétrage ESG - Réglages</li>
    </ol>
</nav>
@endsection
@section('actions')
    <a href="{{ route('admin.evaluation-settings.create') }}" class="btn btn-primary btn-sm">Nouveau</a>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Réglages ESG</h5>
        <p class="lead mb-0">Paramètres globaux (scores minimaux, éligibilités)</p>
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
                    <tr><th>Clé</th><th>Valeur</th><th>Groupe</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($settings as $s)
                        <tr>
                            <td>{{ $s->key }}</td>
                            <td>{{ Str::limit($s->value, 50) }}</td>
                            <td>{{ $s->group ?? '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                        <i class="demo-psi-dot-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="{{ route('admin.evaluation-settings.edit', $s) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.evaluation-settings.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce réglage ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger"><i class="demo-psi-trash me-2"></i> Supprimer</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Aucun réglage.</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $settings->links() }}
        </div>
    </div>
@endsection
