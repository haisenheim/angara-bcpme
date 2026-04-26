@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Analyste conformité',
    'logoutFormId' => 'logout-form-analyste-conformite',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('analyste-conformite.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'analyste-conformite.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
