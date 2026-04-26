@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Directeur général adjoint',
    'logoutFormId' => 'logout-form-dga',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
    $dgaNavDossiersMes = $r === 'dga.dossiers.en-attente-direction';
    $dgaNavDossiersTous = str_starts_with($r, 'dga.dossiers.') && $r !== 'dga.dossiers.en-attente-direction';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('dga.dashboard') }}" class="nav-link mininav-toggle {{ $r === 'dga.dashboard' ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">ACTIVITÉ</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dga.dossiers.en-attente-direction') }}" class="nav-link mininav-toggle {{ $dgaNavDossiersMes ? 'active' : '' }}"><i class="bi bi-inbox fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">En attente avis direction</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dga.dossiers.valides-chef-agence') }}" class="nav-link mininav-toggle {{ $dgaNavDossiersTous ? 'active' : '' }}"><i class="bi bi-collection fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tous les dossiers (validés agence)</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dga.entreprises.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'dga.entreprises') ? 'active' : '' }}"><i class="bi bi-bank fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Clients</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dga.programmes.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'dga.programmes') ? 'active' : '' }}"><i class="bi bi-diagram-3 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Programmes</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">ADMINISTRATION</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dga.users.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'dga.users') || str_starts_with($r, 'dga.user.') ? 'active' : '' }}"><i class="bi bi-people fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Comptes utilisateurs</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
