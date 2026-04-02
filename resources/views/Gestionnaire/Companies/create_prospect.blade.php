@extends('Layouts.gestionnaire')

@section('title', 'Nouveau prospect')
@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.index') }}">Entreprises</a></li>
        <li class="breadcrumb-item"><a href="{{ route('gestionnaire.entreprises.prospects') }}">Prospects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouveau prospect</li>
    </ol>
</nav>
@endsection

@section('actions')
    <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-outline-secondary btn-sm"><i class="demo-pli-arrow-left me-2"></i>Retour à la liste</a>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouveau prospect</h5>
        <p class="text-body-secondary mb-0">Enregistrement d'une entreprise non validée (prospection). Les données pourront être complétées plus tard.</p>
    </div>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-9">
            <div class="card border-0 shadow-sm angara-filter-card">
                <div class="card-body p-4 p-lg-5">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('gestionnaire.entreprises.prospects.store') }}" method="post" id="form-prospect">
                        @csrf
                        <h6 class="text-body-secondary text-uppercase small fw-semibold mb-3">Identification</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="name" class="form-label">Dénomination <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required class="form-control" placeholder="Raison sociale ou nom commercial">
                            </div>
                            <div class="col-md-4">
                                <label for="filter-region" class="form-label">Région</label>
                                <select id="filter-region" class="form-select">
                                    <option value="">Choisir…</option>
                                    @foreach($regions as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="filter-departement" class="form-label">Département</label>
                                <select id="filter-departement" class="form-select" disabled>
                                    <option value="">Choisir…</option>
                                    @foreach($departements as $d)
                                        <option value="{{ $d->id }}" data-region="{{ $d->region_id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="arrondissement_id" class="form-label">Commune <span class="text-danger">*</span></label>
                                <select name="arrondissement_id" id="arrondissement_id" class="form-select @error('arrondissement_id') is-invalid @enderror" required disabled>
                                    <option value="">Choisir…</option>
                                    @foreach($arrondissements as $a)
                                        <option value="{{ $a->id }}" data-departement="{{ $a->departement_id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                                @error('arrondissement_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h6 class="text-body-secondary text-uppercase small fw-semibold mb-3">Compléments</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label for="forme_id" class="form-label">Forme juridique</label>
                                <select name="forme_id" id="forme_id" class="form-select">
                                    <option value="">—</option>
                                    @foreach($formes as $f)
                                        <option value="{{ $f->id }}" @selected(old('forme_id') == $f->id)>{{ $f->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="taille" class="form-label">Taille</label>
                                <select name="taille" id="taille" class="form-select">
                                    <option value="">—</option>
                                    <option value="GRANDE" @selected(old('taille') === 'GRANDE')>Grande</option>
                                    <option value="MOYENNE" @selected(old('taille') === 'MOYENNE')>Moyenne</option>
                                    <option value="PETITE" @selected(old('taille') === 'PETITE')>Petite</option>
                                    <option value="TRES PETITE" @selected(old('taille') === 'TRES PETITE')>Très petite</option>
                                    <option value="COOPERATIVE" @selected(old('taille') === 'COOPERATIVE')>Coopérative</option>
                                    <option value="ASSOCIATION" @selected(old('taille') === 'ASSOCIATION')>Association</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="caractere" class="form-label">Caractère</label>
                                <select name="caractere" id="caractere" class="form-select">
                                    <option value="">—</option>
                                    <option value="Formel" @selected(old('caractere') === 'Formel')>Formel</option>
                                    <option value="Informel" @selected(old('caractere') === 'Informel')>Informel</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="rccm" class="form-label">N° RCCM</label>
                                <input type="text" name="rccm" id="rccm" value="{{ old('rccm') }}" class="form-control" placeholder="Si connu">
                            </div>
                            <div class="col-md-4">
                                <label for="niu" class="form-label">NIU</label>
                                <input type="text" name="niu" id="niu" value="{{ old('niu') }}" class="form-control" placeholder="Si connu">
                            </div>
                            <div class="col-md-4">
                                <label for="manager" class="form-label">Dirigeant</label>
                                <input type="text" name="manager" id="manager" value="{{ old('manager') }}" class="form-control" placeholder="Nom du dirigeant">
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contact">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="contact@…">
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2 justify-content-end pt-2 border-top">
                            <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="btn btn-outline-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="demo-psi-add me-2"></i>Enregistrer le prospect
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const selRegion = document.getElementById('filter-region');
        const selDept = document.getElementById('filter-departement');
        const selArr = document.getElementById('arrondissement_id');

        function syncDepartements() {
            const rid = selRegion.value;
            selDept.querySelectorAll('option').forEach(function(o) {
                if (o.value === '') { o.hidden = false; return; }
                o.hidden = rid && o.dataset.region !== rid;
            });
            var depOpt = selDept.querySelector('option[value="' + selDept.value + '"]');
            if (selDept.value && depOpt && depOpt.hidden) {
                selDept.value = '';
            }
            selDept.disabled = !rid;
            syncArrondissements();
        }

        function syncArrondissements() {
            const did = selDept.value;
            selArr.querySelectorAll('option').forEach(function(o) {
                if (o.value === '') { o.hidden = false; return; }
                o.hidden = did && o.dataset.departement !== did;
            });
            var arrOpt = selArr.querySelector('option[value="' + selArr.value + '"]');
            if (selArr.value && arrOpt && arrOpt.hidden) {
                selArr.value = '';
            }
            selArr.disabled = !did;
        }

        selRegion.addEventListener('change', function() {
            selDept.value = '';
            syncDepartements();
        });
        selDept.addEventListener('change', syncArrondissements);

        syncDepartements();
        @if(old('arrondissement_id'))
            (function() {
                const opt = selArr.querySelector('option[value="{{ old('arrondissement_id') }}"]');
                if (opt) {
                    const dId = opt.dataset.departement;
                    const dOpt = selDept.querySelector('option[value="' + dId + '"]');
                    if (dOpt && dOpt.dataset.region) {
                        selRegion.value = dOpt.dataset.region;
                        syncDepartements();
                        selDept.value = dId;
                        syncArrondissements();
                        selArr.value = '{{ old('arrondissement_id') }}';
                    }
                }
            })();
        @endif
    });
    </script>
@endsection
