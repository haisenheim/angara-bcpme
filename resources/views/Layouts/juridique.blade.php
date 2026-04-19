@extends('Layouts.app')
@section('top')
<?php
$agence = auth()->user()->agence;
?>
<div class="d-flex flex-wrap gap-3 align-items-center">
    <div>
        @if($agence)
            <span class="badge bg-white text-dark fs-6">{{ $agence->name }}@if($agence->representation) — {{ $agence->representation->name }}@endif</span>
        @endif
    </div>
    <div>
        <span class="text-white-50">Connecté en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">Responsable juridique</span></strong>
    </div>
</div>
@endsection

@section('navigation')
@php
    $r = request()->route()?->getName() ?? '';
@endphp
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
        <li class="nav-item">
            <a href="{{ route('juridique.dashboard') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.dashboard') ? 'active' : '' }}"><i class="demo-pli-home fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de bord</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('juridique.entreprises.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.entreprises') ? 'active' : '' }}"><i class="pli-bank fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Entreprises</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('juridique.prospects.index') }}" class="nav-link mininav-toggle {{ str_starts_with($r, 'juridique.prospects') ? 'active' : '' }}"><i class="pli-phone-2 fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Prospects</span>
            </a>
        </li>
    </ul>
</div>
@yield('modal')
@endsection
