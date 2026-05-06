@extends('Layouts.admin')

@section('title', $item->name)

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.organismes.index') }}">Organismes</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
        </ol>
    </nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead">Détails de l'organisme</p>
    </div>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li><a href="#" data-bs-toggle="modal" data-bs-target="#editOrganismeModal" class="dropdown-item">Modifier</a></li>
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
                <dt class="col-sm-3">Abréviation</dt>
                <dd class="col-sm-9">{{ $item->abb ?: '-' }}</dd>
                <dt class="col-sm-3">Type</dt>
                <dd class="col-sm-9">{{ $item->type?->name ?? '-' }}</dd>
                <dt class="col-sm-3">Organisme parent</dt>
                <dd class="col-sm-9">{{ $item->parent?->name ?? '-' }}</dd>
            </dl>

            @if($item->children->isNotEmpty())
                <h6 class="mt-3">Organismes rattachés</h6>
                <ul>
                    @foreach($item->children as $child)
                        <li><a href="{{ route('admin.organismes.show', $child->id) }}">{{ $child->name }}</a></li>
                    @endforeach
                </ul>
            @endif

            <form method="post" action="{{ route('admin.organismes.destroy', $item->id) }}" onsubmit="return confirm('Supprimer cet organisme ?');" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Supprimer cet organisme</button>
            </form>
        </div>
    </div>

    <div class="modal fade" id="editOrganismeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier l'organisme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.organismes.update', $item->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label">Nom</label>
                                <input type="text" required name="name" class="form-control" value="{{ old('name', $item->name) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Abréviation</label>
                                <input type="text" name="abb" maxlength="20" class="form-control" value="{{ old('abb', $item->abb) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select required name="type_id" class="form-control">
                                    <option value="">Sélectionner...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" @selected(old('type_id', $item->type_id) == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Organisme parent</label>
                                <select name="parent_id" class="form-control">
                                    <option value="0">Aucun</option>
                                    @foreach($parents as $p)
                                        @if($p->id !== $item->id)
                                            <option value="{{ $p->id }}" @selected(old('parent_id', $item->parent_id) == $p->id)>{{ $p->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            @if($pays->isNotEmpty())
                                <div class="col-md-6">
                                    <label class="form-label">Pays</label>
                                    <select name="pay_id" class="form-control">
                                        <option value="0">Aucun</option>
                                        @foreach($pays as $p)
                                            <option value="{{ $p->id }}" @selected(old('pay_id', $item->pay_id) == $p->id)>{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
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
