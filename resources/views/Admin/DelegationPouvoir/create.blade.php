@extends('Layouts.admin')

@section('title', 'Nouvelle règle de délégation')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.delegation-pouvoirs.index') }}">Délégation de pouvoir</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nouvelle règle</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Nouvelle règle</h5>
        <p class="text-body-secondary mb-0 mt-1">Exemple : seuil 10&nbsp;000&nbsp;000 → Chef d’agence ; seuil 100&nbsp;000&nbsp;000 → Responsable engagements.</p>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('admin.delegation-pouvoirs.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="seuil">Seuil maximal (XAF)</label>
                    <input type="number" name="seuil_engagements_max" id="seuil" class="form-control @error('seuil_engagements_max') is-invalid @enderror" value="{{ old('seuil_engagements_max') }}" min="0" step="1" required>
                    @error('seuil_engagements_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">Si le total « engagements sollicités » du dossier est ≤ à ce montant, le profil choisi peut valider ou rejeter.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="profil_id">Profil habilité</label>
                    <select name="profil_id" id="profil_id" class="form-select @error('profil_id') is-invalid @enderror" required>
                        <option value="">— Choisir —</option>
                        @foreach($profils as $p)
                            <option value="{{ $p->id }}" @selected((string) old('profil_id') === (string) $p->id)>{{ $p->name }} ({{ $p->abb }})</option>
                        @endforeach
                    </select>
                    @error('profil_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('admin.delegation-pouvoirs.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
