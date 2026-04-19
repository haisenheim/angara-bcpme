@extends('Layouts.juridique')

@section('title', 'Espace juridique')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace juridique</h1>
        <p class="text-muted mb-0">Relecture des prospects soumis avant passage en client.</p>
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
    </div>
@endsection
