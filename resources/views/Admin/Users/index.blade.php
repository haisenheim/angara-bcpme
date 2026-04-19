@extends('Layouts.admin')

@section('title', 'Utilisateurs')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item active" aria-current="page">Utilisateurs</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">Comptes utilisateurs</h5>
        <p class="lead mb-0">Pilotage, filtrage et activation des comptes de l'espace admin.</p>
    </div>
@endsection

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="demo-pli-add me-2 fs-5"></i> Nouveau compte
    </a>
@endsection

@section('content')
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-body-secondary d-block mb-1">Total</small>
                    <h3 class="mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-body-secondary d-block mb-1">Actifs</small>
                    <h3 class="mb-0 text-success">{{ $stats['active'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <small class="text-body-secondary d-block mb-1">Verrouilles</small>
                    <h3 class="mb-0 text-danger">{{ $stats['locked'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="q" class="form-label">Recherche</label>
                        <input id="q" type="text" name="q" value="{{ $filters['q'] }}" class="form-control" placeholder="Nom, email ou telephone">
                    </div>
                    <div class="col-md-3">
                        <label for="role_id" class="form-label">Role</label>
                        <select id="role_id" name="role_id" class="form-control">
                            <option value="">Tous les roles</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected($filters['role_id'] === (string) $role->id)>{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="active" class="form-label">Statut</label>
                        <select id="active" name="active" class="form-control">
                            <option value="">Tous</option>
                            <option value="1" @selected($filters['active'] === '1')>Actif</option>
                            <option value="0" @selected($filters['active'] === '0')>Verrouille</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($items->isEmpty())
                <div class="text-center py-5 text-body-secondary">
                    Aucun compte utilisateur ne correspond aux criteres courants.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Utilisateur</th>
                                <th>Role</th>
                                <th>Affectation</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $item->name }}</div>
                                        <div class="text-body-secondary small">{{ $item->email }}</div>
                                        <div class="text-body-secondary small">{{ $item->phone ?: '-' }}</div>
                                    </td>
                                    <td>{{ $item->role?->name ?? '-' }}</td>
                                    <td>
                                        <div class="small">Agence: {{ $item->agence?->name ?? '-' }}</div>
                                        <div class="small">Direction: {{ $item->agence?->representation?->name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Actions
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('admin.users.show', $item->token) }}">Voir</a></li>
                                                <li><a class="dropdown-item" href="{{ route('admin.users.edit', $item->token) }}">Modifier</a></li>
                                                @if($item->active)
                                                    <li><a class="dropdown-item text-danger" href="{{ route('admin.user.disable', $item->token) }}">Verrouiller</a></li>
                                                @else
                                                    <li><a class="dropdown-item text-success" href="{{ route('admin.user.enable', $item->token) }}">Activer</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
