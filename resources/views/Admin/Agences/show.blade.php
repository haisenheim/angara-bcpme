@extends('Layouts.admin')

@section('title', $item->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.agences.index') }}">Agences</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
        </ol>
    </nav>
@endsection

@section('page-header')
    <div class="d-flex align-items-center gap-2">
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span>
    </div>
    <p class="lead">Détails de l'agence</p>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li><a href="#" data-bs-toggle="modal" data-bs-target="#editAgenceModal" class="dropdown-item">Modifier</a></li>
        @if($item->active)
            <li><a href="{{ route('admin.agences.disable', $item->id) }}" class="dropdown-item">Verrouiller</a></li>
        @else
            <li><a href="{{ route('admin.agences.enable', $item->id) }}" class="dropdown-item">Activer</a></li>
        @endif
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

                <dt class="col-sm-3">Direction régionale</dt>
                <dd class="col-sm-9">{{ $item->representation?->name ?? '-' }}</dd>

                <dt class="col-sm-3">Statut</dt>
                <dd class="col-sm-9"><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></dd>
            </dl>

            <form method="post" action="{{ route('admin.agences.destroy', $item->id) }}" onsubmit="return confirm('Supprimer définitivement cette agence ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer cette agence</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editAgenceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'agence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.agences.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" required name="name" class="form-control" value="{{ old('name', $item->name) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Direction régionale</label>
                            <select required name="representation_id" class="form-control">
                                <option value="">Sélectionner...</option>
                                @foreach($representations as $rep)
                                    <option value="{{ $rep->id }}" @selected(old('representation_id', $item->representation_id) == $rep->id)>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="active" value="0">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="agenceActiveEdit" @checked(old('active', $item->active))>
                            <label class="form-check-label" for="agenceActiveEdit">Agence active</label>
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
