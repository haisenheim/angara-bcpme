@extends('Layouts.ca')

@section('title', 'Espace chef d\'agence')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace chef d'agence</h1>
        <p class="text-muted mb-0">Décision finale sur l'entrée en relation et déclenchement de l'instruction.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects soumis à arbitrage : <strong>{{ $pendingCount }}</strong></p>
                <p class="mb-3">Dossiers EER en attente de creation d'instruction : <strong>{{ $instructionCount }}</strong></p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('chef-agence.prospects.index') }}" class="btn btn-primary btn-sm">Ouvrir la file chef d'agence</a>
                    <a href="{{ route('chef-agence.instructions.index') }}" class="btn btn-outline-secondary btn-sm">Validations instruction</a>
                </div>
            </div>
        </div>
    </div>
@endsection
