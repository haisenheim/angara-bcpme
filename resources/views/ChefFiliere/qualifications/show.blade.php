@extends('Layouts.gestionnaire')

@section('title', 'Détail qualification')

@section('page-header')
    <div>
        <h1 class="h3 mb-0">{{ $item->name }}</h1>
        <p class="text-muted mb-0">Préparation de la qualification et de l'affectation programme.</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Agence</small>
                        <p class="mb-0 mt-2">{{ $item->agence?->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Promotion client</small>
                        <p class="mb-0 mt-2">{{ optional($item->promu_client_at)->format('d/m/Y H:i') ?? '-' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <small class="text-muted text-uppercase">Statut EER</small>
                        <p class="mb-0 mt-2"><span class="badge bg-info">{{ $eer->statut }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <strong>Qualification et identification des besoins</strong>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('chef-filiere.qualifications.update', $item->token) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Analyse strategique</label>
                                <textarea name="analyse_strategique" class="form-control" rows="4">{{ old('analyse_strategique', $eer->analyse_strategique) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Analyse operationnelle</label>
                                <textarea name="analyse_operationnelle" class="form-control" rows="4">{{ old('analyse_operationnelle', $eer->analyse_operationnelle) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Eligibilite</label>
                                <textarea name="analyse_eligibilite" class="form-control" rows="4">{{ old('analyse_eligibilite', $eer->analyse_eligibilite) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Identification des besoins</label>
                                <textarea name="identification_besoins" class="form-control" rows="4">{{ old('identification_besoins', $eer->identification_besoins) }}</textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="besoin_financement" name="besoin_financement" value="1" {{ old('besoin_financement', $eer->besoin_financement) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="besoin_financement">Besoin financement</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="besoin_accompagnement" name="besoin_accompagnement" value="1" {{ old('besoin_accompagnement', $eer->besoin_accompagnement) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="besoin_accompagnement">Besoin accompagnement</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="besoin_structuration" name="besoin_structuration" value="1" {{ old('besoin_structuration', $eer->besoin_structuration) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="besoin_structuration">Besoin structuration</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="form-label">Notes de qualification</label>
                                <textarea name="qualification_notes" class="form-control" rows="4">{{ old('qualification_notes', $eer->qualification_notes) }}</textarea>
                            </div>
                            <div class="mt-3 text-end">
                                <button type="submit" class="btn btn-primary">Enregistrer la qualification</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <strong>Affectation programme</strong>
                        <form method="post" action="{{ route('chef-filiere.qualifications.submit', $item->token) }}">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Soumettre au chef d'agence</button>
                        </form>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('chef-filiere.qualifications.programmes.save', $item->token) }}">
                            @csrf
                            @php $selected = $eer->programmeSelections->keyBy('programme_id'); @endphp
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Programme</th>
                                            <th>Type appui</th>
                                            <th>Notes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programmes as $programme)
                                            @php $selection = $selected->get($programme->id); @endphp
                                            <tr>
                                                <td><input type="checkbox" name="programmes[]" value="{{ $programme->id }}" {{ $selection ? 'checked' : '' }}></td>
                                                <td>{{ $programme->name }}</td>
                                                <td>
                                                    <select name="type_appui[{{ $programme->id }}]" class="form-select form-select-sm">
                                                        <option value="financier" {{ ($selection?->type_appui === 'financier') ? 'selected' : '' }}>Financier</option>
                                                        <option value="non_financier" {{ ($selection?->type_appui === 'non_financier') ? 'selected' : '' }}>Non financier</option>
                                                        <option value="mixte" {{ ($selection?->type_appui === 'mixte') ? 'selected' : '' }}>Mixte</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control form-control-sm" name="programme_notes[{{ $programme->id }}]" value="{{ $selection?->notes }}"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-outline-primary">Enregistrer l'affectation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <strong>Checklist pieces exigibles</strong>
                    </div>
                    <div class="card-body">
                        @if($checklist->isEmpty())
                            <p class="text-muted mb-0">Aucune piece parametree par l'administrateur.</p>
                        @else
                            <ul class="list-group list-group-flush">
                                @foreach($checklist as $row)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span>{{ $row['definition']->label }}</span>
                                        <span class="badge bg-{{ $row['fourni'] ? 'success' : 'secondary' }}">{{ $row['fourni'] ? 'fourni' : 'manquant' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <div class="mt-3">
                                <a href="{{ route('gestionnaire.entreprises.pieces-exigibles.index', $item->token) }}" class="btn btn-outline-secondary btn-sm">Ouvrir la checklist</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white">
                        <strong>Tiers et reponses EER</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>Tiers:</strong> {{ $item->tiers->count() }}</p>
                        <p class="mb-2"><strong>Questionnaire de mise en relation:</strong> {{ $item->reponses()->count() }} reponses</p>
                        <p class="mb-0"><strong>Programmes deja lies au client:</strong> {{ $item->programmes->pluck('name')->filter()->implode(', ') ?: 'aucun' }}</p>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white">
                        <strong>Analyse critique</strong>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Le dossier d'analyse critique consolide les avis juridique, conformite, qualification et instruction.</p>
                        <a href="{{ route('gestionnaire.entreprises.analyse-critique.show', $item->token) }}" class="btn btn-outline-primary btn-sm">Ouvrir l'analyse critique</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
