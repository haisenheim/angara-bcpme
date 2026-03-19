@extends('Layouts.admin')

@section('title', $evaluationThreshold->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-thresholds.index') }}">Seuils</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $evaluationThreshold->name }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $evaluationThreshold->name }}</h5>
        <p class="lead mb-0">Détail du seuil de scoring</p>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $evaluationThreshold->name }}</p>
            <p><strong>Type :</strong> {{ $evaluationThreshold->threshold_type }}</p>
            <a href="{{ route('admin.evaluation-thresholds.edit', $evaluationThreshold) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>
@endsection
