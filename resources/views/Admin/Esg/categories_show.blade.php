@extends('Layouts.admin')

@section('title', $evaluationCategory->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-categories.index') }}">Catégories</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $evaluationCategory->name }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $evaluationCategory->name }}</h5>
        <p class="lead mb-0">Détail de la catégorie d'évaluation</p>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <p><strong>Nom :</strong> {{ $evaluationCategory->name }}</p>
            <p><strong>Code :</strong> {{ $evaluationCategory->code }}</p>
            <a href="{{ route('admin.evaluation-categories.edit', $evaluationCategory) }}" class="btn btn-primary">Modifier</a>
        </div>
    </div>
@endsection
