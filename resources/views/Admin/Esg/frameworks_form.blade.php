@extends('Layouts.admin')

@section('title', isset($evaluationFramework) ? 'Modifier framework' : 'Nouveau framework')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-frameworks.index') }}">Frameworks</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ isset($evaluationFramework) ? 'Modifier' : 'Nouveau' }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ isset($evaluationFramework) ? 'Modifier le framework' : 'Nouveau framework' }}</h5>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="framework-form" action="{{ isset($evaluationFramework) ? route('admin.evaluation-frameworks.update', $evaluationFramework) : route('admin.evaluation-frameworks.store') }}" method="POST">
                @csrf
                @if(isset($evaluationFramework)) @method('PUT') @endif
                <div class="mb-2">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $evaluationFramework->name ?? '') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $evaluationFramework->code ?? '') }}" required {{ isset($evaluationFramework) ? 'readonly' : '' }}>
                </div>
                <div class="form-check mb-2">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $evaluationFramework->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Actif</label>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.evaluation-frameworks.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="framework-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
