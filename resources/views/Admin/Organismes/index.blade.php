@extends('Layouts.admin')

@section('title', 'Organismes')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item">Paramètres</li>
            <li class="breadcrumb-item active" aria-current="page">Organismes</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addOrganismeModal" data-bs-toggle="modal" class="dropdown-item">
                <i class="demo-pli-add me-2 fs-5"></i> Ajouter un organisme
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Organismes</h5>
        <p class="lead">Liste des bailleurs et partenaires de financement</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-2 mb-3">
                <div class="col-md-6">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher (nom, abréviation)..." value="{{ $filters['q'] }}">
                </div>
                <div class="col-md-4">
                    <select name="type_id" class="form-control">
                        <option value="">Tous les types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->id }}" @selected((string) $filters['type_id'] === (string) $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Filtrer</button></div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Abrév.</th>
                            <th>Type</th>
                            <th>Parent</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><a href="{{ route('admin.organismes.show', $item->id) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->abb ?: '-' }}</td>
                                <td>{{ $item->type?->name ?? '-' }}</td>
                                <td>{{ $item->parent?->name ?? '-' }}</td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('admin.organismes.show', $item->id) }}">Afficher</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="post" action="{{ route('admin.organismes.destroy', $item->id) }}" onsubmit="return confirm('Supprimer cet organisme ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">Supprimer</button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-body-secondary py-4">Aucun organisme</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addOrganismeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvel organisme</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.organismes.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-9">
                                <label class="form-label">Nom</label>
                                <input type="text" required name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Abréviation</label>
                                <input type="text" name="abb" maxlength="20" class="form-control" value="{{ old('abb') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Type</label>
                                <select required name="type_id" class="form-control">
                                    <option value="">Sélectionner...</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type->id }}" @selected(old('type_id') == $type->id)>{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Organisme parent (facultatif)</label>
                                <select name="parent_id" class="form-control">
                                    <option value="0">Aucun</option>
                                    @foreach($parents as $p)
                                        <option value="{{ $p->id }}" @selected(old('parent_id') == $p->id)>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($pays->isNotEmpty())
                                <div class="col-md-6">
                                    <label class="form-label">Pays (facultatif)</label>
                                    <select name="pay_id" class="form-control">
                                        <option value="0">Aucun</option>
                                        @foreach($pays as $p)
                                            <option value="{{ $p->id }}" @selected(old('pay_id') == $p->id)>{{ $p->name }}</option>
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
