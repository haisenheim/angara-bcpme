@extends('Layouts.admin')

@section('title', 'Fiche utilisateur')

@section('breadcrumb')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Angara</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Utilisateurs</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $item->name }}</li>
    </ol>
</nav>
@endsection

@section('page-header')
    <div>
        <h5 class="page-title mb-0 mt-2">{{ $item->name }}</h5>
        <p class="lead mb-0">Consultation detaillee du compte utilisateur.</p>
    </div>
@endsection

@section('actions')
    <x-page-actions-dropdown>
        <li>
            <a href="{{ route('admin.users.edit', $item->token) }}" class="dropdown-item">Modifier</a>
        </li>
        @if($item->active)
            <li>
                <a href="{{ route('admin.user.disable', $item->token) }}" class="dropdown-item text-danger">Verrouiller</a>
            </li>
        @else
            <li>
                <a href="{{ route('admin.user.enable', $item->token) }}" class="dropdown-item text-success">Activer</a>
            </li>
        @endif
    </x-page-actions-dropdown>
@endsection

@section('content')
    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Compte</h6>
                    <div class="mb-2"><span class="text-body-secondary">Nom:</span> {{ $item->name }}</div>
                    <div class="mb-2"><span class="text-body-secondary">Email:</span> {{ $item->email }}</div>
                    <div class="mb-2"><span class="text-body-secondary">Telephone:</span> {{ $item->phone ?: '-' }}</div>
                    <div class="mb-2"><span class="text-body-secondary">Role:</span> {{ $item->role?->name ?? '-' }}</div>
                    <div><span class="text-body-secondary">Statut:</span> <span class="badge bg-{{ $item->status['color'] }}">{{ $item->status['name'] }}</span></div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Affectations</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-body-secondary d-block">{{ ($item->organisation_type ?? null) === 'entite' ? 'Entite' : 'Agence' }}</small>
                                <div class="fw-semibold">
                                    @if(($item->organisation_type ?? null) === 'entite')
                                        {{ $item->organisationEntite?->name ?? '-' }}
                                    @else
                                        {{ $item->agence?->name ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-body-secondary d-block">{{ ($item->organisation_type ?? null) === 'entite' ? 'Type' : 'Direction' }}</small>
                                <div class="fw-semibold">
                                    @if(($item->organisation_type ?? null) === 'entite')
                                        {{ $item->organisationEntite?->type ?? '-' }}
                                    @else
                                        {{ $item->agence?->representation?->name ?? '-' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
