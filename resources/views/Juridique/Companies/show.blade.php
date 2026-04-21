@extends('Layouts.juridique')

@section('title', $item->name)

@section('page-header')
    <div>
        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('juridique.entreprises.index') }}">Entreprises</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($item->name, 48) }}</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">{{ $item->name }}</h1>
        <p class="text-muted mb-0">Fiche entreprise (consultation)</p>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        @include('partials.entreprise-chef-agence-decision', ['item' => $item])
        @include('partials.entreprise-qualification-chef-filiere', ['item' => $item, 'qualificationContext' => 'juridique'])
        <div class="card shadow-sm">
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">NIU</dt>
                    <dd class="col-sm-9">{{ $item->niu ?? '—' }}</dd>
                    <dt class="col-sm-3">RCCM</dt>
                    <dd class="col-sm-9">{{ $item->rccm ?? '—' }}</dd>
                    <dt class="col-sm-3">Agence</dt>
                    <dd class="col-sm-9">{{ $item->agence?->name ?? '—' }}</dd>
                    <dt class="col-sm-3">Région</dt>
                    <dd class="col-sm-9">{{ $item->region?->name ?? '—' }}</dd>
                    <dt class="col-sm-3">Arrondissement</dt>
                    <dd class="col-sm-9">{{ $item->arrondissement?->name ?? '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
