@extends('Layouts.ca')

@section('title', 'Validations ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Validations ESG</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Validations ESG</h5>
        <p class="lead">Évaluations ESG de l'agence</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('ca.esg-evaluations.dashboard') }}" class="btn btn-outline-primary">Dashboard ESG</a>
    </div>

    <div class="row mb-3">
        <div class="col"><span class="badge bg-warning">Soumises : {{ $submitted->count() }}</span></div>
        <div class="col"><span class="badge bg-success">Validées : {{ $validated->count() }}</span></div>
        <div class="col"><span class="badge bg-danger">Rejetées : {{ $rejected->count() }}</span></div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Programme</th>
                            <th>Analyste</th>
                            <th>Statut</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($evaluations as $ev)
                            <tr>
                                <td>{{ $ev->dossier?->entreprise?->name }}</td>
                                <td>{{ $ev->dossier?->programme?->name }}</td>
                                <td>{{ $ev->analyste?->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $ev->status === 'validated' ? 'success' : ($ev->status === 'submitted' ? 'warning' : ($ev->status === 'rejected' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($ev->status) }}
                                    </span>
                                </td>
                                <td>{{ $ev->score_global }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i class="demo-psi-dot-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('ca.esg-evaluations.show', $ev) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                            @if($ev->isSubmitted())
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('ca.esg-evaluations.validate', $ev) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success"><i class="demo-psi-check me-2"></i> Valider</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $ev->id }}">
                                                        <i class="demo-psi-close me-2"></i> Rejeter
                                                    </button>
                                                </li>
                                                @include('Ca.Esg.partials.reject_modal', ['evaluation' => $ev])
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">Aucune évaluation.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
