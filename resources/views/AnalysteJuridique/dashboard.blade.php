@extends('Layouts.analyste-juridique')

@section('title', 'Espace analyste juridique')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0">Tableau de bord</h5>
        <p class="text-muted mb-0">Dossiers d’instruction qui vous sont affectés par le responsable juridique.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <p class="mb-3">Consultez la liste des dossiers et saisissez votre avis lorsque vous y êtes affecté.</p>
                <a href="{{ route('analyste-juridique.dossiers.index') }}" class="btn btn-primary">Ouvrir les dossiers d’instruction</a>
            </div>
        </div>
    </div>
@endsection
