@extends('Layouts.analyste')

@section('title', ($evaluation ? 'Modifier' : 'Créer') . ' évaluation ESG')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('analyste.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('analyste.dossiers.esg-evaluations.index') }}">Évaluations ESG</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $evaluation ? 'Modifier' : 'Créer' }} - {{ $dossier->entreprise?->name }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $evaluation ? 'Modifier' : 'Créer' }} l'évaluation ESG</h5>
        <p class="lead">{{ $dossier->entreprise?->name }} - {{ $dossier->programme?->name }}</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="esg-evaluation-form" action="{{ $evaluation ? route('analyste.dossiers.esg-evaluation.update', $dossier) : route('analyste.dossiers.esg-evaluation.store', $dossier) }}" method="POST">
                @csrf
                @if($evaluation) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label">Score environnemental (0-100)</label>
                            <input type="number" name="score_environmental" class="form-control" value="{{ old('score_environmental', $evaluation->score_environmental ?? 0) }}" min="0" max="100" step="0.01">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Score social (0-100)</label>
                            <input type="number" name="score_social" class="form-control" value="{{ old('score_social', $evaluation->score_social ?? 0) }}" min="0" max="100" step="0.01">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Score gouvernance (0-100)</label>
                            <input type="number" name="score_governance" class="form-control" value="{{ old('score_governance', $evaluation->score_governance ?? 0) }}" min="0" max="100" step="0.01">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label class="form-label">Score financier (0-100)</label>
                            <input type="number" name="score_financial" class="form-control" value="{{ old('score_financial', $evaluation->score_financial ?? 0) }}" min="0" max="100" step="0.01">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Score conformité (0-100)</label>
                            <input type="number" name="score_compliance" class="form-control" value="{{ old('score_compliance', $evaluation->score_compliance ?? 0) }}" min="0" max="100" step="0.01">
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Conclusion analyste</label>
                    <textarea name="analyst_conclusion" class="form-control" rows="3">{{ old('analyst_conclusion', $evaluation->analyst_conclusion ?? '') }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Forces</label>
                    <textarea name="strengths" class="form-control" rows="2">{{ old('strengths', $evaluation->strengths ?? '') }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Faiblesses</label>
                    <textarea name="weaknesses" class="form-control" rows="2">{{ old('weaknesses', $evaluation->weaknesses ?? '') }}</textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Recommandations</label>
                    <textarea name="recommendations" class="form-control" rows="2">{{ old('recommendations', $evaluation->recommendations ?? '') }}</textarea>
                </div>
            </form>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="{{ route('analyste.dossiers.esg-evaluation.show', $dossier) }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" form="esg-evaluation-form" class="btn btn-primary">Enregistrer</button>
        </div>
    </div>
@endsection
