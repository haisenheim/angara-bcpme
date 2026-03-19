@extends('Layouts.admin')

@section('title', isset($evaluationCategory) ? 'Modifier catégorie' : 'Nouvelle catégorie')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-categories.index') }}">Catégories</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ isset($evaluationCategory) ? 'Modifier' : 'Nouvelle' }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ isset($evaluationCategory) ? 'Modifier la catégorie' : 'Nouvelle catégorie' }}</h5>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="category-form" action="{{ isset($evaluationCategory) ? route('admin.evaluation-categories.update', $evaluationCategory) : route('admin.evaluation-categories.store') }}" method="POST">
                @csrf
                @if(isset($evaluationCategory)) @method('PUT') @endif
                <div class="mb-2">
                    <label class="form-label">Framework</label>
                    <select name="framework_id" class="form-select">
                        <option value="">--</option>
                        @foreach($frameworks as $f)
                            <option value="{{ $f->id }}" {{ old('framework_id', $evaluationCategory->framework_id ?? '') == $f->id ? 'selected' : '' }}>{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $evaluationCategory->name ?? '') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $evaluationCategory->code ?? '') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Pondération</label>
                    <input type="number" name="weight" class="form-control" value="{{ old('weight', $evaluationCategory->weight ?? 1) }}" step="0.01">
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.evaluation-categories.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="category-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
