@extends('Layouts.analyste-risques')

@section('title', 'Espace analyste risques')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace analyste risques</h1>
        <p class="text-muted mb-0">Analyse des risques et avis sur les dossiers qui vous sont affectés par le responsable risques.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-3">Dossiers du pôle risques qui vous sont affectés : <strong>{{ $dossiersCount }}</strong></p>
                <a href="{{ route('analyste-risques.dossiers.index') }}" class="btn btn-primary">Voir les dossiers</a>
            </div>
        </div>
    </div>
@endsection
