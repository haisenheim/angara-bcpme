@extends('Layouts.ca')

@section('title', 'Dashboard ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('ca.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item active" aria-current="page">Dashboard ESG</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Dashboard ESG - Agence</h5>
        <p class="lead">Synthèse des évaluations ESG</p>
    </div>
@endsection

@section('content')
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['total'] }}</h3>
                    <p class="mb-0">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-warning text-dark">
                <div class="card-body text-center">
                    <h3>{{ $stats['submitted'] }}</h3>
                    <p class="mb-0">Soumises</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['validated'] }}</h3>
                    <p class="mb-0">Validées</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['rejected'] }}</h3>
                    <p class="mb-0">Rejetées</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3>{{ $stats['avg_score'] }}</h3>
                    <p class="mb-0">Score moyen</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>Répartition par niveau de risque</strong></div>
        <div class="card-body">
            @forelse($riskDistribution as $level => $count)
                <p>{{ $level ?? 'Non défini' }} : {{ $count }}</p>
            @empty
                <p>Aucune donnée.</p>
            @endforelse
        </div>
    </div>
    <a href="{{ route('ca.esg-evaluations.index') }}" class="btn btn-secondary mt-2">Voir les évaluations</a>
@endsection
