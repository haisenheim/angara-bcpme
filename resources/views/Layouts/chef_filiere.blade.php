@extends('Layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chef-filiere.css') }}">
@endpush

@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Chef de filière',
    'logoutFormId' => 'logout-form-chef-filiere',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
    $instructionOpen = str_starts_with($r, 'chef-filiere.instructions.');
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('chef-filiere.dashboard') }}" class="nav-link mininav-toggle {{ $r === 'chef-filiere.dashboard' ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('chef-filiere.qualifications.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'chef-filiere.qualifications.') ? 'active' : '' }}"><i class="bi bi-check2-square fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Structurations en attente</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('chef-filiere.clients.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'chef-filiere.clients.') ? 'active' : '' }}"><i class="bi bi-buildings fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Clients</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('chef-filiere.programmes.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'chef-filiere.programmes.') ? 'active' : '' }}"><i class="bi bi-diagram-3 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Programmes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#nav-chef-instructions" class="nav-link mininav-toggle d-flex align-items-center justify-content-between {{ $instructionOpen ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#nav-chef-instructions" aria-expanded="{{ $instructionOpen ? 'true' : 'false' }}" role="button" aria-controls="nav-chef-instructions">
                <span><i class="bi bi-folder2 fs-5 me-2"></i><span class="nav-label mininav-content ms-1">Dossiers d'instruction</span></span>
                <i class="bi bi-chevron-down fs-6"></i>
            </a>
            <div class="collapse {{ $instructionOpen ? 'show' : '' }}" id="nav-chef-instructions">
                <ul class="nav flex-column ms-3 ps-2 border-start border-secondary border-opacity-25 mt-1 gap-1">
                    <li class="nav-item">
                        <a href="{{ route('chef-filiere.instructions.pending') }}" class="nav-link py-1 {{ $r === 'chef-filiere.instructions.pending' ? 'active' : '' }}">
                            <span class="small">En attente</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('chef-filiere.instructions.in-progress') }}" class="nav-link py-1 {{ $r === 'chef-filiere.instructions.in-progress' || $r === 'chef-filiere.instructions.dossier.show' ? 'active' : '' }}">
                            <span class="small">En cours</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>
@include('partials.layout-tdb-link')

<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">COMPTE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('profile') }}" class="nav-link mininav-toggle {{ $r === 'profile' ? 'active' : '' }}"><i class="bi bi-person-circle fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Mon profil</span>
            </a>
        </li>
    </ul>
</div>
@yield('modal')
@endsection
