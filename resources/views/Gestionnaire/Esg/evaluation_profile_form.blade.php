@extends('Layouts.gestionnaire')

@section('title', ($profile ? 'Modifier' : 'Créer') . ' profil ESG - ' . $entreprise->name)
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.evaluation-profiles.index') }}">Profils ESG</a></li>
       <li class="breadcrumb-item active" aria-current="page">{{ $profile ? 'Modifier' : 'Créer' }} - {{ $entreprise->name }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $profile ? 'Modifier' : 'Créer' }} le profil ESG - {{ $entreprise->name }}</h5>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ $profile ? route('gestionnaire.entreprises.evaluation-profile.update', $entreprise) : route('gestionnaire.entreprises.evaluation-profile.store', $entreprise) }}" method="POST">
                @csrf
                @if($profile) @method('PUT') @endif

                <div class="row">
                    <div class="col-md-6">
                        <h6>Informations générales</h6>
                        <div class="mb-2">
                            <label class="form-label">Secteur d'activité</label>
                            <input type="text" name="business_sector" class="form-control" value="{{ old('business_sector', $profile->business_sector ?? '') }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Chiffre d'affaires annuel</label>
                            <input type="number" name="annual_turnover" class="form-control" value="{{ old('annual_turnover', $profile->annual_turnover ?? '') }}" step="0.01">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Effectif total</label>
                            <input type="number" name="employees_total" class="form-control" value="{{ old('employees_total', $profile->employees_total ?? 0) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Environnement</h6>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="env_policy" value="1" class="form-check-input" {{ old('env_policy', $profile->env_policy ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Politique environnementale</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="env_certified" value="1" class="form-check-input" {{ old('env_certified', $profile->env_certified ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Certification environnementale</label>
                        </div>
                        <h6 class="mt-3">Social</h6>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="women_led" value="1" class="form-check-input" {{ old('women_led', $profile->women_led ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Dirigée par des femmes</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="youth_led" value="1" class="form-check-input" {{ old('youth_led', $profile->youth_led ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Dirigée par des jeunes</label>
                        </div>
                        <h6 class="mt-3">Gouvernance</h6>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="has_board" value="1" class="form-check-input" {{ old('has_board', $profile->has_board ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">Conseil d'administration</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="audited_financials" value="1" class="form-check-input" {{ old('audited_financials', $profile->audited_financials ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label">États financiers audités</label>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <a href="{{ route('gestionnaire.entreprises.evaluation-profile.show', $entreprise) }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
