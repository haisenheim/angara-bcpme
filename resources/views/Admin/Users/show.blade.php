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
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.edit', $item->token) }}" class="btn btn-primary btn-sm">Modifier</a>
        @if($item->active)
            <a href="{{ route('admin.user.disable', $item->token) }}" class="btn btn-outline-danger btn-sm">Verrouiller</a>
        @else
            <a href="{{ route('admin.user.enable', $item->token) }}" class="btn btn-outline-success btn-sm">Activer</a>
        @endif
    </div>
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
                                <small class="text-body-secondary d-block">Agence</small>
                                <div class="fw-semibold">{{ $item->agence?->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <small class="text-body-secondary d-block">Direction</small>
                                <div class="fw-semibold">{{ $item->agence?->representation?->name ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
