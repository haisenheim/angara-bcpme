@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Responsable risques',
    'logoutFormId' => 'logout-form-rerx',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('rerx.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'rerx.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">SUIVI</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('rerx.dossiers.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'rerx.dossiers') ? 'active' : '' }}"><i class="bi bi-folder2 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers (depuis engagements)</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('rerx.entreprises.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'rerx.entreprises') ? 'active' : '' }}"><i class="bi bi-bank fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Entreprises</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-tdb-link')
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
