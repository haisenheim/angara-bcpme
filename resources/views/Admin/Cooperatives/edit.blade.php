@extends('Layouts.admin')

@section('title', 'Modifier coopérative')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.cooperatives.index') }}">Coopératives</a></li>
       <li class="breadcrumb-item"><a href="{{ route('admin.cooperatives.show', $item->token) }}">{{ $item->name }}</a></li>
       <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
 </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Modifier la coopérative</h5>
        <p class="lead">{{ $item->name }}</p>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Modifier les informations</h6>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" action="{{ route('admin.cooperatives.update', $item->token) }}" method="post">
                        @csrf
                        @method('PUT')

                        <fieldset class="mb-4">
                            <legend class="h6 border-bottom pb-2">Informations générales</legend>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label for="name" class="form-label">Nom de la coopérative <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $item->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="dtn" class="form-label">Date de création</label>
                                    <input type="date" name="dtn" id="dtn"
                                           class="form-control"
                                           value="{{ old('dtn', $item->dtn ? $item->dtn->format('Y-m-d') : '') }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="domaine_id" class="form-label">Domaine d'activité <span class="text-danger">*</span></label>
                                    <select name="domaine_id" id="domaine_id"
                                            class="form-select @error('domaine_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un domaine</option>
                                        @foreach($domaines as $domaine)
                                            <option value="{{ $domaine->id }}"
                                                    {{ old('domaine_id', $item->domaine_id) == $domaine->id ? 'selected' : '' }}>
                                                {{ $domaine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('domaine_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="secteur_id" class="form-label">Secteur</label>
                                    <select name="secteur_id" id="secteur_id" class="form-select">
                                        <option value="">Sélectionner un secteur (optionnel)</option>
                                        @foreach($secteurs as $secteur)
                                            <option value="{{ $secteur->id }}"
                                                    {{ old('secteur_id', $item->secteur_id) == $secteur->id ? 'selected' : '' }}>
                                                {{ $secteur->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" name="phone" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $item->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="email" class="form-label">Email de contact</label>
                                    <input type="email" name="m-email" id="email"
                                           class="form-control"
                                           value="{{ old('m-email', $item->email) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="photo" class="form-label">Logo/Photo (nouveau)</label>
                                    <input type="file" name="photo" id="photo"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">Laisser vide pour conserver l'actuel</small>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="address" class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" name="address" id="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           value="{{ old('address', $item->address) }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mb-4">
                            <legend class="h6 border-bottom pb-2">Localisation</legend>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="region_id" class="form-label">Région</label>
                                    <select name="region_id" id="region_id" class="form-select">
                                        <option value="">Sélectionner une région</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="departement_id" class="form-label">Département</label>
                                    <select name="departement_id" id="departement_id" class="form-select">
                                        <option value="">Sélectionner un département</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="arrondissement_id" class="form-label">Arrondissement <span class="text-danger">*</span></label>
                                    <select name="arrondissement_id" id="arrondissement_id"
                                            class="form-select @error('arrondissement_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un arrondissement</option>
                                        @foreach($arrondissements as $arr)
                                            <option value="{{ $arr->id }}"
                                                    data-departement="{{ $arr->departement_id }}"
                                                    data-region="{{ $arr->departement->region_id }}"
                                                    {{ old('arrondissement_id', $item->arrondissement_id) == $arr->id ? 'selected' : '' }}>
                                                {{ $arr->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('arrondissement_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </fieldset>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('admin.cooperatives.show', $item->token) }}" class="btn btn-outline-secondary">
                                <i class="demo-psi-arrow-left me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="demo-psi-check me-2"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cascade functionality for location selects
        document.getElementById('region_id').addEventListener('change', function() {
            const regionId = this.value;
            if (!regionId) return;

            fetch(`{{ route("util.region.departements") }}?id=${regionId}`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('departement_id');
                    select.innerHTML = '<option value="">Sélectionner un département</option>';
                    data.forEach(dept => {
                        select.innerHTML += `<option value="${dept.id}">${dept.name}</option>`;
                    });
                    document.getElementById('arrondissement_id').innerHTML = '<option value="">Sélectionner un arrondissement</option>';
                })
                .catch(error => console.error('Error loading departements:', error));
        });

        document.getElementById('departement_id').addEventListener('change', function() {
            const departementId = this.value;
            if (!departementId) return;

            fetch(`{{ route("util.departement.arrondissements") }}?id=${departementId}`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('arrondissement_id');
                    select.innerHTML = '<option value="">Sélectionner un arrondissement</option>';
                    data.forEach(arr => {
                        select.innerHTML += `<option value="${arr.id}">${arr.name}</option>`;
                    });
                })
                .catch(error => console.error('Error loading arrondissements:', error));
        });

        // Auto-populate region and departement based on selected arrondissement
        document.getElementById('arrondissement_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const departementId = selectedOption.getAttribute('data-departement');
            const regionId = selectedOption.getAttribute('data-region');

            if (regionId) {
                document.getElementById('region_id').value = regionId;
            }
            if (departementId) {
                document.getElementById('departement_id').value = departementId;
            }
        });
    </script>

    <style>
        fieldset {
            padding: 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        legend {
            width: auto;
            padding: 0 0.5rem;
            margin-bottom: 0;
            font-size: 1rem;
        }
    </style>
@endsection

