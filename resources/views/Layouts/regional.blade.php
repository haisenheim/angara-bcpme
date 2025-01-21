@extends('Layouts.app')
@section('top')
<?php
$region = \Illuminate\Support\Facades\Session::get('region');
?>
<div class="d-flex gap-3">
    <div>
        <span><span class="badge bg-white text-dark fs-6">{{ $region?->name }} </span> </span>
    </div>
    <div>
        <span>Connecté  en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">Responsable regional</span></strong>
    </div>
</div>
@endsection
@section('navigation')
 <!-- Navigation Category -->
 <?php
    $active = \Illuminate\Support\Facades\Session::get('active');
?>
     <!-- Navigation Category -->
     <div class="mainnav__categoriy py-3">
        
        <ul class="mainnav__menu nav flex-column gap-2">
           <li class="nav-item">
               <a href="{{ route('regional.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-3 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Tableau de board</span>
               </a>
           </li>


        <!-- Link with submenu -->
         <li class="nav-item has-sub">
            <a href="#" class="mininav-toggle nav-link {{ ($active>200&&$active<300)?'active':'' }}"><i class="pli-folders fs-5 me-2"></i>
                <span class="nav-label ms-1">DOSSIERS</span>
            </a>
            <!-- Settings submenu list -->
            <ul class="mininav-content nav collapse">
                <li class="nav-item">
                    <a href="{{ route('regional.dossiers.index') }}" class="nav-link {{ $active==201?'active':'' }}">DOSSIERS D'INSTRUCTION</a>
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

           <li class="nav-item">
                <a href="{{ route('regional.programmes.index') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}"><i class="pli-affiliate fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">PROGRAMMES</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('regional.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">ENTREPRISES</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('regional.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}"><i class="pli-phone-2 fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">PROSPECTS</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('regional.users.index') }}" class="nav-link mininav-toggle {{ $active==6?'active':'' }}"><i class="pli-conference fs-2 me-2"></i>
                    <span class="nav-label mininav-content ms-1">Comptes utilisateurs</span>
                </a>
            </li>

            <li class="nav-item has-sub">
                <a href="#" class="mininav-toggle nav-link {{ ($active>700&&$active<800)?'active':'' }}"><i class="pli-map fs-5 me-2"></i>
                    <span class="nav-label ms-1">Territore</span>
                </a>
                <!-- Settings submenu list -->
                <ul class="mininav-content nav collapse">
                    <li class="nav-item">
                        <a href="{{ route('regional.territoire') }}" class="nav-link {{ $active==701?'active':'' }}">Organisation administrative</a>
                    </li>
                </ul>
                <!-- END : Dashboard submenu list -->
            </li>
            <!-- END : Link with submenu -->
        </ul>
    </div>
    <!-- END : Navigation Category -->
@endsection
