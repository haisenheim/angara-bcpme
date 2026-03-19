@extends('Layouts.analyste')

@section('title', 'Évaluations ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.dossiers.index') }}">Dossiers</a></li>
       <li class="breadcrumb-item active" aria-current="page">Évaluations ESG</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Évaluations ESG des dossiers</h5>
        <p class="lead">Dossiers affectés avec état de l'évaluation ESG</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Dossier</th>
                            <th>Programme</th>
                            <th>Statut ESG</th>
                            <th>Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dossiers as $dossier)
                            @php $esg = $dossier->esgEvaluation; @endphp
                            <tr>
                                <td>{{ $dossier->entreprise?->name }}</td>
                                <td>{{ $dossier->programme?->name }}</td>
                                <td>
                                    @if($esg)
                                        <span class="badge bg-{{ $esg->status === 'validated' ? 'success' : ($esg->status === 'submitted' ? 'warning' : ($esg->status === 'rejected' ? 'danger' : 'info')) }}">
                                            {{ ucfirst($esg->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Non créée</span>
                                    @endif
                                </td>
                                <td>{{ $esg?->score_global ?? '-' }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i class="demo-psi-dot-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            @if($esg)
                                                <li><a class="dropdown-item" href="{{ route('analyste.dossiers.esg-evaluation.show', $dossier) }}"><i class="demo-psi-eye me-2"></i> Voir</a></li>
                                                @if($esg->canBeEdited())
                                                    <li><a class="dropdown-item" href="{{ route('analyste.dossiers.esg-evaluation.edit', $dossier) }}"><i class="demo-psi-pen-5 me-2"></i> Modifier</a></li>
                                                @endif
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('analyste.dossiers.esg-evaluation.create', $dossier) }}"><i class="demo-pli-add me-2"></i> Évaluer</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center">Aucun dossier affecté.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
