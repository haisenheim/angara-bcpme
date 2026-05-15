@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Responsable juridique',
    'logoutFormId' => 'logout-form-juridique',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('juridique.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.dashboard') ? 'active' : '' }}"><i class="bi bi-house-door fs-4 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE (CONSULTATION)</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('juridique.tous-prospects.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.tous-prospects') ? 'active' : '' }}"><i class="bi bi-people fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tous les prospects (soumis)</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('juridique.entreprises.index') }}" class="nav-link mininav-toggle {{ $r === 'juridique.entreprises.index' ? 'active' : '' }}"><i class="bi bi-bank fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Entreprises &amp; clients</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('juridique.portefeuille.dossiers.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.portefeuille.dossiers') ? 'active' : '' }}"><i class="bi bi-folder2-open fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tous les dossiers d’instruction</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PÔLE JURIDIQUE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('juridique.dossiers.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.dossiers') && ! str_starts_with($r, 'juridique.portefeuille.') ? 'active' : '' }}"><i class="bi bi-folder2 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers transmis au pôle</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('juridique.prospects.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.prospects') ? 'active' : '' }}"><i class="bi bi-telephone fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">File avis juridique (prospects)</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-tdb-link')
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
