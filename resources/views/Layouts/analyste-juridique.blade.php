@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Analyste juridique',
    'logoutFormId' => 'logout-form-analyste-juridique',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('analyste-juridique.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'analyste-juridique.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">INSTRUCTION</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('analyste-juridique.dossiers.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'analyste-juridique.dossiers') ? 'active' : '' }}"><i class="bi bi-folder2 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers d’instruction</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
