@extends('Layouts.conformite')

@section('title', 'Espace conformité')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace conformité</h1>
        <p class="text-muted mb-0">Validation conformité après avis juridique.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects en attente d'avis conformité : <strong>{{ $pendingCount }}</strong></p>
                <a href="{{ route('conformite.prospects.index') }}" class="btn btn-primary btn-sm">Ouvrir la file conformité</a>
            </div>
        </div>
    </div>
@endsection
