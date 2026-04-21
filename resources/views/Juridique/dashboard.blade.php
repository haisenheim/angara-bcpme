@extends('Layouts.juridique')

@section('title', 'Espace juridique')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace juridique</h1>
        <p class="text-muted mb-0">Relecture des prospects et dossiers d’instruction transmis par l’exploitation.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects en attente d'avis juridique : <strong>{{ $pendingCount }}</strong></p>
                <a href="{{ route('juridique.prospects.index') }}" class="btn btn-primary btn-sm">Ouvrir la file juridique</a>
            </div>
        </div>
        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <p class="mb-2">Dossiers d’instruction transmis (exploitation) : <strong>{{ $instructionDossiersCount ?? 0 }}</strong></p>
                <a href="{{ route('juridique.dossiers.index') }}" class="btn btn-outline-primary btn-sm">Ouvrir les dossiers d’instruction</a>
            </div>
        </div>
    </div>
@endsection
