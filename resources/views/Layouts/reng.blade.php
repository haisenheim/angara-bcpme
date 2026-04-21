@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Responsable engagements',
    'logoutFormId' => 'logout-form-reng',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('reng.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'reng.dashboard') ? 'active' : '' }}"><i class="demo-pli-home fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('reng.dossiers.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'reng.dossiers') ? 'active' : '' }}"><i class="demo-psi-folder fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers d’instruction</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('reng.entreprises.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'reng.entreprises') ? 'active' : '' }}"><i class="pli-bank fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Entreprises</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
