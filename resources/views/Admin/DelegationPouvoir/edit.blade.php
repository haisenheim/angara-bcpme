@extends('Layouts.admin')

@section('title', 'Modifier la règle de délégation')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.delegation-pouvoirs.index') }}">Délégation de pouvoir</a></li>
        <li class="breadcrumb-item active" aria-current="page">Modifier</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Modifier la règle</h5>
    </div>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="post" action="{{ route('admin.delegation-pouvoirs.update', $item) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label" for="seuil">Seuil maximal (XAF)</label>
                    <input type="number" name="seuil_engagements_max" id="seuil" class="form-control @error('seuil_engagements_max') is-invalid @enderror" value="{{ old('seuil_engagements_max', $item->seuil_engagements_max) }}" min="0" step="1" required>
                    @error('seuil_engagements_max')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label" for="profil_id">Profil habilité</label>
                    <select name="profil_id" id="profil_id" class="form-select @error('profil_id') is-invalid @enderror" required>
                        <option value="">— Choisir —</option>
                        @foreach($profils as $p)
                            <option value="{{ $p->id }}" @selected((string) old('profil_id', $item->profil_id) === (string) $p->id)>{{ $p->name }} ({{ $p->abb }})</option>
                        @endforeach
                    </select>
                    @error('profil_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="{{ route('admin.delegation-pouvoirs.index') }}" class="btn btn-outline-secondary">Annuler</a>
            </form>
        </div>
    </div>
@endsection
