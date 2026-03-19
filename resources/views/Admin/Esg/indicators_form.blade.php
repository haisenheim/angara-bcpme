@extends('Layouts.admin')

@section('title', isset($evaluationIndicator) ? 'Modifier indicateur' : 'Nouvel indicateur')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-indicators.index') }}">Indicateurs</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ isset($evaluationIndicator) ? 'Modifier' : 'Nouvel' }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ isset($evaluationIndicator) ? 'Modifier l\'indicateur' : 'Nouvel indicateur' }}</h5>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="indicator-form" action="{{ isset($evaluationIndicator) ? route('admin.evaluation-indicators.update', $evaluationIndicator) : route('admin.evaluation-indicators.store') }}" method="POST">
                @csrf
                @if(isset($evaluationIndicator)) @method('PUT') @endif
                <div class="mb-2">
                    <label class="form-label">Catégorie</label>
                    <select name="category_id" class="form-select">
                        <option value="">--</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ old('category_id', $evaluationIndicator->category_id ?? '') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $evaluationIndicator->name ?? '') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Code</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $evaluationIndicator->code ?? '') }}" required>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.evaluation-indicators.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="indicator-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
