@extends('Layouts.app')
@section('top')
<div class="d-flex gap-3">
    <div>
        <span>Connecté  en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">Administrateur</span></strong>
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
               <a href="{{ route('admin.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-5 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Tableau de board</span>
               </a>
           </li>

           <li class="nav-item">
               <a href="{{ route('admin.transactions.index') }}" class="nav-link mininav-toggle {{ $active==2?'active':'' }}"><i class="pli-sync fs-5 me-2"></i>
                   <span class="nav-label mininav-content ms-1">Transactions</span>
               </a>
           </li>

           <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link mininav-toggle {{ $active==2?'active':'' }}"><i class="pli-conference fs-5 me-2"></i>
                <span class="nav-label mininav-content ms-1">Utilisateurs</span>
            </a>
        </li>


            <!-- Link with submenu -->
            <li class="nav-item has-sub">
                <a href="#" class="mininav-toggle nav-link {{ ($active>700&&$active<800)?'active':'' }}"><i class="demo-pli-gears fs-5 me-2"></i>
                    <span class="nav-label ms-1">Parametres</span>
                </a>
                <!-- Settings submenu list -->
                <ul class="mininav-content nav collapse">
                    <li class="nav-item">
                        <a href="{{ route('admin.libelles.index') }}" class="nav-link {{ $active==701?'active':'' }}">Libellés d'ecriture</a>
                    </li>
                   <li class="nav-item">
                       <a href="{{ route('admin.caisses.index') }}" class="nav-link {{ $active==702?'active':'' }}">Caisses</a>
                   </li>
                   <li class="nav-item">
                       <a href="{{ route('admin.agences.index') }}" class="nav-link {{ $active==703?'active':'' }}">Agences</a>
                   </li>
                   <li class="nav-item">
                    <a href="{{ route('admin.tiers.index') }}" class="nav-link {{ $active==705?'active':'' }}">Tiers</a>
                </li>
                   <li class="nav-item">
                       <a href="{{ route('admin.comptes.index') }}" class="nav-link {{ $active==706?'active':'' }}">Comptes</a>
                   </li>
                </ul>
                <!-- END : Dashboard submenu list -->
            </li>
            <!-- END : Link with submenu -->

        </ul>
    </div>
    <!-- END : Navigation Category -->
@endsection