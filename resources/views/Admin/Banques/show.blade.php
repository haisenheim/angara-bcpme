@extends('Layouts.admin')

@section('title', $item->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.banques.index') }}">Banques</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
        </ol>
    </nav>
@endsection

@section('page-header')
    <div class="d-flex align-items-center gap-2">
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        @if($item->microfinance)
            <span class="badge bg-info">Microfinance</span>
        @else
            <span class="badge bg-primary">Banque</span>
        @endif
    </div>
    <p class="lead">Détails de l'établissement financier</p>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li><a href="#" data-bs-toggle="modal" data-bs-target="#editBanqueModal" class="dropdown-item">Modifier</a></li>
    </x-page-actions-dropdown>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <dl class="row">
                <dt class="col-sm-3">Nom</dt>
                <dd class="col-sm-9">{{ $item->name }}</dd>
                <dt class="col-sm-3">Siège</dt>
                <dd class="col-sm-9">{{ $item->siege ?: '-' }}</dd>
                <dt class="col-sm-3">Adresse</dt>
                <dd class="col-sm-9" style="white-space: pre-line">{{ $item->address ?: '-' }}</dd>
                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">
                    @if($item->microfinance)
                        <span class="badge bg-info">Microfinance</span>
                    @else
                        <span class="badge bg-primary">Banque</span>
                    @endif
                </dd>
            </dl>

            <form method="post" action="{{ route('admin.banques.destroy', $item->id) }}" onsubmit="return confirm('Supprimer cet établissement ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer cet établissement</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editBanqueModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'établissement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.banques.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nom</label>
                                <input type="text" required name="name" class="form-control" value="{{ old('name', $item->name) }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Siège</label>
                                <input type="text" name="siege" class="form-control" value="{{ old('siege', $item->siege) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Adresse</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address', $item->address) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="hidden" name="microfinance" value="0">
                                    <input class="form-check-input" type="checkbox" name="microfinance" value="1" id="banqueMicroEdit" @checked(old('microfinance', $item->microfinance))>
                                    <label class="form-check-label" for="banqueMicroEdit">Établissement de microfinance</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
