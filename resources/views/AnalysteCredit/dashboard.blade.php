@extends('Layouts.analyste-credit')

@section('title', 'Espace analyste crédit')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace analyste crédit</h1>
        <p class="text-muted mb-0">Dossiers du pôle engagements qui vous sont affectés par le responsable engagements.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-3">Dossiers qui vous sont affectés : <strong>{{ $dossiersCount }}</strong></p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('analyste-credit.dossiers.index') }}" class="btn btn-primary">Voir les dossiers</a>
                    <a href="{{ route('analyste-credit.entreprises.index') }}" class="btn btn-outline-primary">Entreprises</a>
                </div>
            </div>
        </div>
    </div>
@endsection
