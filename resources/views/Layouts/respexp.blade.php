@extends('Layouts.app')

@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Responsable exploitation',
    'logoutFormId' => 'logout-form-respexp',
])
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
    $dossiersOpen = str_starts_with($r, 'respexp.dossiers');
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('respexp.dashboard') }}" class="nav-link mininav-toggle {{ $r === 'respexp.dashboard' ? 'active' : '' }}"><i class="demo-pli-home fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Accueil</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">INSTRUCTION</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="#nav-respexp-dossiers" class="nav-link mininav-toggle d-flex align-items-center justify-content-between {{ $dossiersOpen ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#nav-respexp-dossiers" aria-expanded="{{ $dossiersOpen ? 'true' : 'false' }}" role="button" aria-controls="nav-respexp-dossiers">
                <span><i class="pli-folder fs-2 me-2"></i><span class="nav-label mininav-content ms-1">Dossiers d'instruction</span></span>
                <i class="demo-pli-arrow-down fs-6"></i>
            </a>
            <div class="collapse {{ $dossiersOpen ? 'show' : '' }}" id="nav-respexp-dossiers">
                <ul class="nav flex-column ms-3 ps-2 border-start border-secondary border-opacity-25 mt-1 gap-1">
                    <li class="nav-item">
                        <a href="{{ route('respexp.dossiers.index') }}" class="nav-link py-1 {{ $r === 'respexp.dossiers.index' && ! request('filter') ? 'active' : '' }}">
                            <span class="small">Tous les dossiers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('respexp.dossiers.index', ['filter' => 'a_affecter']) }}" class="nav-link py-1 {{ request('filter') === 'a_affecter' ? 'active' : '' }}">
                            <span class="small">À affecter (sans analyste)</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('respexp.entreprises.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'respexp.entreprises') ? 'active' : '' }}"><i class="demo-pli-building fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Entreprises</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
@yield('modal')
@endsection
