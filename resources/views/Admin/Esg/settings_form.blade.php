@extends('Layouts.admin')

@section('title', isset($evaluationSetting) ? 'Modifier réglage' : 'Nouveau réglage')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.evaluation-settings.index') }}">Réglages</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ isset($evaluationSetting) ? 'Modifier' : 'Nouveau' }}</li>
    </ol>
</nav>
@endsection
@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ isset($evaluationSetting) ? 'Modifier le réglage' : 'Nouveau réglage' }}</h5>
    </div>
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <form id="setting-form" action="{{ isset($evaluationSetting) ? route('admin.evaluation-settings.update', $evaluationSetting) : route('admin.evaluation-settings.store') }}" method="POST">
                @csrf
                @if(isset($evaluationSetting)) @method('PUT') @endif
                <div class="mb-2">
                    <label class="form-label">Clé</label>
                    <input type="text" name="key" class="form-control" value="{{ old('key', $evaluationSetting->key ?? '') }}" required {{ isset($evaluationSetting) ? 'readonly' : '' }}>
                </div>
                <div class="mb-2">
                    <label class="form-label">Valeur</label>
                    <input type="text" name="value" class="form-control" value="{{ old('value', $evaluationSetting->value ?? '') }}">
                </div>
                <div class="mb-2">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        @foreach(['string','json','boolean','integer','decimal'] as $t)
                            <option value="{{ $t }}" {{ old('type', $evaluationSetting->type ?? 'string') == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('admin.evaluation-settings.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="setting-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
