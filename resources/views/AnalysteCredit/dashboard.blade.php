@extends('Layouts.app')

@section('title', 'Espace analyste crédit')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">Espace analyste crédit</h1>
        <p class="text-muted mb-0">Espace dédié aux analyses crédit / risque dans les workflows BC-PME.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">
                <p class="mb-2">Dossiers actuellement rattachés à l'agence : <strong>{{ $dossiersCount }}</strong></p>
                <p class="text-muted mb-0">L'espace est prêt et peut maintenant recevoir les écrans métier spécifiques à l'analyse crédit.</p>
            </div>
        </div>
    </div>
@endsection
