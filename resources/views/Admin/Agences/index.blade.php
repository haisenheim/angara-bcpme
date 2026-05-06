@extends('Layouts.admin')

@section('title', 'Agences')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item">Réseau</li>
            <li class="breadcrumb-item active" aria-current="page">Agences</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addAgenceModal" data-bs-toggle="modal" class="dropdown-item">
                <i class="demo-pli-add me-2 fs-5"></i> Ajouter une agence
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Agences</h5>
        <p class="lead">Liste des agences rattachées aux directions régionales</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><span class="text-body-secondary small">Total</span><h3 class="mb-0">{{ $stats['total'] }}</h3></div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><span class="text-success small">Actives</span><h3 class="mb-0">{{ $stats['active'] }}</h3></div></div>
        </div>
        <div class="col-md-4">
            <div class="card h-100"><div class="card-body"><span class="text-danger small">Verrouillées</span><h3 class="mb-0">{{ $stats['locked'] }}</h3></div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-2 mb-3">
                <div class="col-md-5">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher une agence..." value="{{ $filters['q'] }}">
                </div>
                <div class="col-md-3">
                    <select name="representation_id" class="form-control">
                        <option value="">Toutes les directions</option>
                        @foreach($representations as $rep)
                            <option value="{{ $rep->id }}" @selected((string) $filters['representation_id'] === (string) $rep->id)>{{ $rep->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="active" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="1" @selected($filters['active'] === '1')>Actives</option>
                        <option value="0" @selected($filters['active'] === '0')>Verrouillées</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary w-100" type="submit">Filtrer</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Agence</th>
                            <th>Direction régionale</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><a href="{{ route('admin.agences.show', $item->id) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->representation?->name ?? '-' }}</td>
                                <td><span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('admin.agences.show', $item->id) }}">Afficher</a></li>
                                            @if($item->active)
                                                <li><a class="dropdown-item" href="{{ route('admin.agences.disable', $item->id) }}">Verrouiller</a></li>
                                            @else
                                                <li><a class="dropdown-item" href="{{ route('admin.agences.enable', $item->id) }}">Activer</a></li>
                                            @endif
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="post" action="{{ route('admin.agences.destroy', $item->id) }}" onsubmit="return confirm('Supprimer définitivement cette agence ?');">
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
                            <tr><td colspan="4" class="text-center text-body-secondary py-4">Aucune agence</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addAgenceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvelle agence</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.agences.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom de l'agence</label>
                            <input type="text" required name="name" class="form-control" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Direction régionale</label>
                            <select required name="representation_id" class="form-control">
                                <option value="">Sélectionner une direction régionale</option>
                                @foreach($representations as $rep)
                                    <option value="{{ $rep->id }}" @selected(old('representation_id') == $rep->id)>{{ $rep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check">
                            <input type="hidden" name="active" value="0">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="agenceActive" checked>
                            <label class="form-check-label" for="agenceActive">Agence active</label>
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
