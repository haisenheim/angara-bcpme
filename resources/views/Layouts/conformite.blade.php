@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Responsable conformité',
    'logoutFormId' => 'logout-form-conformite',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('conformite.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'conformite.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">CONFORMITÉ</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('conformite.prospects.index') }}" class="nav-link mininav-toggle {{ $r === 'conformite.prospects.index' ? 'active' : '' }}"><i class="bi bi-shield-check fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">File avis conformité</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('conformite.prospects.treated') }}" class="nav-link mininav-toggle {{ $r === 'conformite.prospects.treated' ? 'active' : '' }}"><i class="bi bi-archive fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers traités</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-tdb-link')
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
