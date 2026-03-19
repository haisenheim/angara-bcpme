@extends('Layouts.admin')

@section('title', $evaluationIndicator->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-indicators.index') }}">Indicateurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $evaluationIndicator->name }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $evaluationIndicator->name }}</h5>
        <p class="lead mb-0">Détail de l'indicateur d'évaluation</p>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $evaluationIndicator->name }}</p>
            <p><strong>Code :</strong> {{ $evaluationIndicator->code }}</p>
            <a href="{{ route('admin.evaluation-indicators.edit', $evaluationIndicator) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>
@endsection
