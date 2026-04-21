@extends('Layouts.analyste-conformite')

@section('title', 'Espace analyste conformité')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace analyste conformité</h1>
        <p class="text-muted mb-0">Espace dédié aux analyses de conformité intervenant dans les workflows BC-PME.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Prospects actuellement soumis dans le circuit : <strong>{{ $pendingProspects }}</strong></p>
                <p class="text-muted mb-0">L'espace est prêt et peut maintenant accueillir les files et formulaires métier conformité.</p>
            </div>
        </div>
    </div>
@endsection
