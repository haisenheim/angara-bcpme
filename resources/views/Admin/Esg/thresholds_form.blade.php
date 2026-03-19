@extends('Layouts.admin')

@section('title', isset($evaluationThreshold) ? 'Modifier seuil' : 'Nouveau seuil')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-thresholds.index') }}">Seuils</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ isset($evaluationThreshold) ? 'Modifier' : 'Nouveau' }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ isset($evaluationThreshold) ? 'Modifier le seuil' : 'Nouveau seuil' }}</h5>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="threshold-form" action="{{ isset($evaluationThreshold) ? route('admin.evaluation-thresholds.update', $evaluationThreshold) : route('admin.evaluation-thresholds.store') }}" method="POST">
                @csrf
                @if(isset($evaluationThreshold)) @method('PUT') @endif
                <div class="mb-2">
                    <label class="form-label">Type</label>
                    <select name="threshold_type" class="form-select" required>
                        <option value="risk" {{ old('threshold_type', $evaluationThreshold->threshold_type ?? '') == 'risk' ? 'selected' : '' }}>Risque</option>
                        <option value="bankability" {{ old('threshold_type', $evaluationThreshold->threshold_type ?? '') == 'bankability' ? 'selected' : '' }}>Bancabilité</option>
                        <option value="eligibility" {{ old('threshold_type', $evaluationThreshold->threshold_type ?? '') == 'eligibility' ? 'selected' : '' }}>Éligibilité</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Nom</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $evaluationThreshold->name ?? '') }}" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Label</label>
                    <input type="text" name="label" class="form-control" value="{{ old('label', $evaluationThreshold->label ?? '') }}" required>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label">Valeur min</label>
                            <input type="number" name="min_value" class="form-control" value="{{ old('min_value', $evaluationThreshold->min_value ?? 0) }}" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label">Valeur max</label>
                            <input type="number" name="max_value" class="form-control" value="{{ old('max_value', $evaluationThreshold->max_value ?? 100) }}" step="0.01">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.evaluation-thresholds.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="threshold-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
