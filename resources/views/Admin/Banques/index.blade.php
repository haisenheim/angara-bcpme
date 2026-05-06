@extends('Layouts.admin')

@section('title', 'Banques')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">ANGARA</a></li>
            <li class="breadcrumb-item">Paramètres</li>
            <li class="breadcrumb-item active" aria-current="page">Banques</li>
        </ol>
    </nav>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="#" data-bs-target="#addBanqueModal" data-bs-toggle="modal" class="dropdown-item">
                <i class="demo-pli-add me-2 fs-5"></i> Ajouter un établissement
            </a>
        </li>
    </x-page-actions-dropdown>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Banques &amp; Microfinances</h5>
        <p class="lead">Liste des établissements financiers partenaires</p>
    </div>
@endsection

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><span class="text-body-secondary small">Total</span><h3 class="mb-0">{{ $stats['total'] }}</h3></div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><span class="text-primary small">Banques</span><h3 class="mb-0">{{ $stats['banques'] }}</h3></div></div></div>
        <div class="col-md-4"><div class="card h-100"><div class="card-body"><span class="text-info small">Microfinances</span><h3 class="mb-0">{{ $stats['microfinances'] }}</h3></div></div></div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-2 mb-3">
                <div class="col-md-7">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher (nom, siège, adresse)..." value="{{ $filters['q'] }}">
                </div>
                <div class="col-md-3">
                    <select name="microfinance" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="0" @selected($filters['microfinance'] === '0')>Banques</option>
                        <option value="1" @selected($filters['microfinance'] === '1')>Microfinances</option>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-primary w-100" type="submit">Filtrer</button></div>
            </form>

            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Siège</th>
                            <th>Type</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td><a href="{{ route('admin.banques.show', $item->id) }}">{{ $item->name }}</a></td>
                                <td>{{ $item->siege ?: '-' }}</td>
                                <td>
                                    @if($item->microfinance)
                                        <span class="badge bg-info">Microfinance</span>
                                    @else
                                        <span class="badge bg-primary">Banque</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-xs btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="{{ route('admin.banques.show', $item->id) }}">Afficher</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="post" action="{{ route('admin.banques.destroy', $item->id) }}" onsubmit="return confirm('Supprimer cet établissement ?');">
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
                            <tr><td colspan="4" class="text-center text-body-secondary py-4">Aucun établissement</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $items->withQueryString()->links() }}
            </div>
        </div>
    </div>

    <div class="modal fade" id="addBanqueModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouvel établissement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.banques.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label">Nom de l'établissement</label>
                                <input type="text" required name="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Siège</label>
                                <input type="text" name="siege" class="form-control" value="{{ old('siege') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Adresse</label>
                                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="hidden" name="microfinance" value="0">
                                    <input class="form-check-input" type="checkbox" name="microfinance" value="1" id="banqueMicro">
                                    <label class="form-check-label" for="banqueMicro">Établissement de microfinance</label>
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
