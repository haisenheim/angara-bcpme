@extends('Layouts.app')
@section('top')
@include('partials.layout-role-header-top', [
    'roleLabel' => 'Gestionnaire',
    'logoutFormId' => 'logout-form-gestionnaire',
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
           <a href="{{ route('gestionnaire.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-3 me-2"></i>
               <span class="nav-label mininav-content ms-1">Accueil</span>
           </a>
       </li>

    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">INTERMEDIATION</h6>
    <ul class="mainnav__menu nav flex-column">

        <li class="nav-item">
            <a href="{{ route('gestionnaire.dossiers.index') }}" class="nav-link mininav-toggle {{ $active==201?'active':'' }}"><i class="pli-folder fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">INSTRUCTION</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
       <li class="nav-item">
           <a href="{{ route('gestionnaire.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==401?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
               <span class="nav-label mininav-content ms-1">Entreprises</span>
           </a>
       </li>
       <li class="nav-item">
           <a href="{{ route('gestionnaire.entites.index') }}" class="nav-link mininav-toggle {{ $active==403?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
               <span class="nav-label mininav-content ms-1">Entités individuelles</span>
           </a>
       </li>
       <li class="nav-item">
            <a href="{{ route('gestionnaire.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==404?'active':'' }}"><i class="pli-phone-2 fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Prospects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('gestionnaire.programmes.index') }}" class="nav-link mininav-toggle {{ $active==405?'active':'' }}"><i class="pli-affiliate fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">programmes</span>
            </a>
        </li>

    </ul>
</div>

@include('partials.layout-role-nav-compte')

@yield('modal')
@endsection
