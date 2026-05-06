@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => "Chef d'agence",
    'logoutFormId' => 'logout-form-ca',
])
@endsection
@section('navigation')
 <!-- Navigation Category -->
 <?php
    $active = \Illuminate\Support\Facades\Session::get('active');
    $r = request()->route()?->getName() ?? '';
?>
<div class="mainnav__categoriy py-3 mb-0">
    <ul class="mainnav__menu nav flex-column gap-2">
       <li class="nav-item">
           <a href="{{ route('ca.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="bi bi-house-door fs-4 me-2"></i>
               <span class="nav-label mininav-content ms-1">Accueil</span>
           </a>
       </li>

    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">INTERMEDIATION</h6>
    <ul class="mainnav__menu nav flex-column">

        <li class="nav-item">
            <a href="{{ route('ca.dossiers.index') }}" class="nav-link mininav-toggle {{ $active==201?'active':'' }}"><i class="bi bi-folder2 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">INSTRUCTION</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
       <li class="nav-item">
           <a href="{{ route('ca.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==401?'active':'' }}"><i class="bi bi-bank fs-5 me-2"></i>
               <span class="nav-label mininav-content ms-1">Entreprises</span>
           </a>
       </li>
       <li class="nav-item">
            <a href="{{ route('ca.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==404?'active':'' }}"><i class="bi bi-telephone fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Prospects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ca.programmes.index') }}" class="nav-link mininav-toggle {{ $active==405?'active':'' }}"><i class="bi bi-diagram-3 fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">programmes</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ca.workflow.prospects.index') }}" class="nav-link mininav-toggle {{ $active==406?'active':'' }}"><i class="bi bi-check2-circle fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Arbitrage prospects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ca.workflow.instructions.index') }}" class="nav-link mininav-toggle {{ $active==407?'active':'' }}"><i class="bi bi-clipboard2-check fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Validations structuration (EER)</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ca.workflow.instruction-dossiers.index') }}" class="nav-link mininav-toggle {{ $active==408?'active':'' }}"><i class="bi bi-folder2-open fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Dossiers instruction (multi-programmes)</span>
            </a>
        </li>

    </ul>
</div>

@include('partials.layout-tdb-link')

<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">Systeme</h6>
    <ul class="mainnav__menu nav flex-column">

        <li class="nav-item">
            <a href="{{ route('ca.users.index') }}" class="nav-link mininav-toggle {{ $active==11?'active':'' }}"><i class="bi bi-people fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Utilisateurs</span>
            </a>
        </li>
    </ul>
</div>
@include('partials.layout-role-nav-compte')
    @yield('modal')
@endsection
