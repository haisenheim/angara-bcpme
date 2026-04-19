@extends('Layouts.app')

@section('title', $space['title'])

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace {{ $space['title'] }}</h1>
        <p class="text-muted mb-0">Consultation transverse des clients, dossiers et pièces du portefeuille.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Entreprises</small>
                        <div class="fs-4 fw-semibold">{{ $stats['entreprises'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Dossiers</small>
                        <div class="fs-4 fw-semibold">{{ $stats['dossiers'] }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <small class="text-muted text-uppercase d-block mb-1">Pièces fournies</small>
                        <div class="fs-4 fw-semibold">{{ $stats['pieces'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body d-flex flex-wrap gap-2">
                <a href="{{ route($space['route'].'.entreprises.index') }}" class="btn btn-primary">Voir les entreprises</a>
                <a href="{{ route($space['route'].'.dossiers.index') }}" class="btn btn-outline-primary">Voir les dossiers</a>
            </div>
        </div>
    </div>
@endsection
