@extends('Layouts.app')
@section('top')
<?php
$program = \Illuminate\Support\Facades\Session::get('program');
?>
<div class="d-flex gap-3">
    <div>
        <span><span class="badge bg-white text-dark fs-6">{{ $program->name }} </span> </span>
    </div>
    <div>
        <span>Connecté  en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">Agent du programme</span></strong>
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
        <h6 class="mainnav__caption mt-0 px-3 fw-bold">Navigation</h6>
        <ul class="mainnav__menu nav flex-column">
           <li class="nav-item">
               <a href="{{ route('program.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-3 me-2"></i>
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
                    <a href="{{ route('program.dossiers.index') }}" class="nav-link {{ $active==201?'active':'' }}">DOSSIERS D'INSTRUCTION</a>
                </li>

            </ul>
            <!-- END : Dashboard submenu list -->
        </li>
        <!-- END : Link with submenu -->

        <li class="nav-item">
            <a href="{{ route('program.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">ENTREPRISES</span>
            </a>
        </li>

        </ul>
    </div>
    <!-- END : Navigation Category -->
@endsection
