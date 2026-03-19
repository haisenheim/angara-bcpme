@extends('Layouts.app')
@section('top')
<?php
$agence = \Illuminate\Support\Facades\Session::get('agence');
?>
<div class="d-flex gap-3">
    <div>
        <span><span class="badge bg-white text-dark fs-6">{{ $agence->name }} - {{ $agence->representation?->name }} </span> </span>
    </div>
    <div>
        <span>Connecté  en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">analyste financier</span></strong>
    </div>
    <div style="display: none">
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <a role="button" class="" onclick="this.parentNode.submit();">
                <span class="ms-1">Se déconnecter</span></a>
        </form>
    </div>
</div>
@endsection
@section('navigation')
 <!-- Navigation Category -->
 <?php
    $active = \Illuminate\Support\Facades\Session::get('active');
?>
     <!-- Navigation Category -->
     <div class="mainnav__categoriy py-3 mb-4">
        <ul class="mainnav__menu nav flex-column gap-2">
           <li class="nav-item">
               <a href="{{ route('analyste.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-3 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Tableau de bord</span>
               </a>
           </li>
        <!-- Link with submenu -->
         <li class="nav-item has-sub">
            <a href="#" class="mininav-toggle nav-link {{ ($active>200&&$active<300)?'active':'' }}"><i class="pli-folders fs-5 me-2"></i>
                <span class="nav-label ms-1">INTERMEDIATION</span>
            </a>
            <!-- Settings submenu list -->
            <ul class="mininav-content nav collapse">
                <li class="nav-item">
                    <a href="{{ route('analyste.dossiers.index') }}" class="nav-link {{ $active==201?'active':'' }}">DOSSIERS D'INSTRUCTION</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('analyste.dossiers.esg-evaluations.index') }}" class="nav-link {{ $active==206?'active':'' }}">ÉVALUATIONS ESG</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $active==202?'active':'' }}">DOSSIERS DE COMPENSATION</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $active==203?'active':'' }}">DOSSIERS D'INVESTISSEMENT</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link {{ $active==201?'active':'' }}">DOSSIERS DE GARANTIE</a>
                </li>

            </ul>
            <!-- END : Dashboard submenu list -->
        </li>
        <!-- END : Link with submenu -->
        </ul>
    </div>

    <div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">

    <li class="nav-item">
                <a href="{{ route('analyste.programmes.index') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}"><i class="pli-affiliate fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">PROGRAMMES</span>
                </a>
            </li>



            <li class="nav-item">
                <a href="{{ route('analyste.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">ENTREPRISES</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('analyste.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}"><i class="pli-phone-2 fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">PROSPECTS</span>
                </a>
            </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3 mb-4">
            <!-- Link with submenu -->
            <li class="nav-item has-sub">
                <a href="#" class="mininav-toggle nav-link {{ ($active>800&&$active<900)?'active':'' }}"><i class="demo-pli-gears fs-5 me-2"></i>
                    <span class="nav-label ms-1">Parametres</span>
                </a>
                <!-- Settings submenu list -->
                <ul class="mininav-content nav collapse">
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $active==801?'active':'' }}">Organismes</a>
                        <a href="#" class="nav-link {{ $active==801?'active':'' }}">Banques</a>
                    </li>

                </ul>
                <!-- END : Dashboard submenu list -->
            </li>
            <!-- END : Link with submenu -->

        </ul>
    </div>
    <!-- END : Navigation Category -->
    @yield('modal')
@endsection
