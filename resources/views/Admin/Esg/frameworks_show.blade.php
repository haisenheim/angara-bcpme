@extends('Layouts.admin')

@section('title', $evaluationFramework->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-frameworks.index') }}">Frameworks</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $evaluationFramework->name }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $evaluationFramework->name }}</h5>
        <p class="lead mb-0">Détail du framework d'évaluation</p>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $evaluationFramework->name }}</p>
            <p><strong>Code :</strong> {{ $evaluationFramework->code }}</p>
            <a href="{{ route('admin.evaluation-frameworks.edit', $evaluationFramework) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>
@endsection
