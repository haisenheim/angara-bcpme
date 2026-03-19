@extends('Layouts.gestionnaire')

@section('title', 'Profils d\'évaluation ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
       <li class="breadcrumb-item active" aria-current="page">Profils d'évaluation ESG</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Profils d'évaluation ESG</h5>
        <p class="lead">Liste des entreprises du portefeuille avec état du profil d'évaluation</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Entreprise</th>
                            <th>Profil ESG</th>
                            <th>Dossier ESG</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entreprises as $entreprise)
                            @php $profile = $profiles[$entreprise->id] ?? null; @endphp
                            <tr>
                                <td>{{ $entreprise->name }}</td>
                                <td>
                                    @if($profile)
                                        <span class="badge bg-success">Complété</span>
                                    @else
                                        <span class="badge bg-secondary">Non créé</span>
                                    @endif
                                </td>
                                <td>
                                    @php $dossier = $entreprise->dossiers->first(); $esg = $dossier?->esgEvaluation; @endphp
                                    @if($esg)
                                        <span class="badge bg-{{ $esg->status === 'validated' ? 'success' : ($esg->status === 'submitted' ? 'warning' : ($esg->status === 'rejected' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($esg->status) }}
                                        </span>
                                        @if($esg->score_global)
                                            <small>({{ $esg->score_global }}/100)</small>
                                        @endif
                                    @else
                                        <span class="badge bg-light text-dark">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i class="demo-psi-dot-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($profile)
                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.evaluation-profile.show', $entreprise) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.evaluation-profile.edit', $entreprise) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('gestionnaire.entreprises.evaluation-profile.create', $entreprise) }}"><i class="demo-pli-add me-2"></i> Créer profil</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">Aucune entreprise dans votre portefeuille.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
