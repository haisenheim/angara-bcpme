@extends('Layouts.chef_filiere')

@section('title', 'Besoins et produits — '.$item->name)

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.clients.index') }}">Clients</a></li>
        <li class="breadcrumb-item"><a href="{{ route('chef-filiere.clients.show', $item->token) }}">{{ Str::limit($item->name, 35) }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Besoins &amp; produits</li>
    </ol>
</nav>
@endsection

@section('actions')
    <x-page-actions-dropdown button-id="chefFiliereBesoinsProduitsActions">
        <li><a class="dropdown-item" href="{{ route('chef-filiere.clients.show', $item->token) }}">Retour à la fiche client</a></li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
<div>
    <h5 class="page-title mb-0">Besoins et produits</h5>
    <p class="text-body-secondary mb-0 mt-1">{{ $item->name }} — mettez à jour les appuis souhaités et la gamme produits.</p>
</div>
@endsection

@section('content')
<div class="cf-page">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    @endif

    <form method="post" action="{{ route('chef-filiere.clients.besoins-produits.update', $item->token) }}" class="card cf-client-card border-0 shadow-sm">
        @csrf
        <div class="card-body p-4">
            <fieldset id="produit-principal" class="mb-5">
                <legend class="h6 fw-semibold border-bottom pb-2 mb-3">Produit principal</legend>
                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="produit_id" class="form-label">Produit principal</label>
                        <select name="produit_id" id="produit_id" class="form-select @error('produit_id') is-invalid @enderror">
                            <option value="">— Aucun —</option>
                            @foreach($produitsListe as $p)
                                <option value="{{ $p->id }}" @selected((int) old('produit_id', $item->produit_id) === (int) $p->id)>
                                    {{ trim(($p->code ? $p->code.' ' : '').$p->name) }}
                                    @if($p->filiere) — {{ $p->filiere->name }} @endif
                                </option>
                            @endforeach
                        </select>
                        @error('produit_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="produit_year_start" class="form-label">Ancienneté (années)</label>
                        <input type="number" name="produit_year_start" id="produit_year_start" class="form-control @error('produit_year_start') is-invalid @enderror"
                               value="{{ old('produit_year_start', $item->produit_year_start) }}" min="0" max="200" placeholder="ex. 5">
                        @error('produit_year_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </fieldset>

            <fieldset id="produits-secondaires" class="mb-5">
                <legend class="h6 fw-semibold border-bottom pb-2 mb-3">Produits secondaires</legend>
                <p class="text-muted small">Sélectionnez un ou plusieurs produits <strong>distincts</strong> du produit principal (Ctrl/Cmd + clic pour plusieurs choix).</p>
                @error('autres')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                <select name="autres[]" id="autres" class="form-select" multiple size="12">
                    @php $oldAutres = collect(old('autres', $selectedSecondaires))->map(fn ($v) => (int) $v)->all(); @endphp
                    @foreach($produitsListe as $p)
                        <option value="{{ $p->id }}" @selected(in_array((int) $p->id, $oldAutres, true))>
                            {{ trim(($p->code ? $p->code.' ' : '').$p->name) }}
                        </option>
                    @endforeach
                </select>
            </fieldset>

            <fieldset id="besoins" class="mb-4">
                <legend class="h6 fw-semibold border-bottom pb-2 mb-3">Besoins exprimés (appuis souhaités)</legend>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Appuis financiers</label>
                        @error('appuisf')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        <select name="appuisf[]" class="form-select" multiple size="10">
                            @php $oldF = collect(old('appuisf', $selectedFinanciers))->map(fn ($v) => (int) $v)->all(); @endphp
                            @foreach($appuisFinanciers as $s)
                                <option value="{{ $s->id }}" @selected(in_array((int) $s->id, $oldF, true))>
                                    {{ $s->name }}@if($s->type) ({{ $s->type->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Appuis non financiers</label>
                        @error('appuisnf')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                        <select name="appuisnf[]" class="form-select" multiple size="10">
                            @php $oldNf = collect(old('appuisnf', $selectedNonFinanciers))->map(fn ($v) => (int) $v)->all(); @endphp
                            @foreach($appuisNonFinanciers as $s)
                                <option value="{{ $s->id }}" @selected(in_array((int) $s->id, $oldNf, true))>
                                    {{ $s->name }}@if($s->type) ({{ $s->type->name }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            <div class="d-flex flex-wrap gap-2 pt-2">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="{{ route('chef-filiere.clients.show', $item->token) }}" class="btn btn-outline-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var h = window.location.hash;
    if (h && document.querySelector(h)) {
        document.querySelector(h).scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});
</script>
@endsection
