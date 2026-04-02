@extends('Layouts.app')
@section('top')
<?php
$agence = \Illuminate\Support\Facades\Session::get('agence');
?>
    <div>
        <div class="d-flex gap-3">
            <div>
                <span><span class="badge bg-white text-dark fs-6">{{ $agence->name }} - {{ $agence->representation?->name }} </span> </span>
            </div>
            <div>
                <span>-</span>
                <strong><span class="badge bg-white text-dark fs-6">gestionnaire</span></strong>
            </div>
        </div>
    </div>
    <div class="header__content-end">


                  <!-- User dropdown -->
                  <div class="dropdown">

                     <!-- Toggler -->
                     <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-label="User dropdown" aria-expanded="false">
                        <?php
                                $uxx = \Illuminate\Support\Facades\Session::get('user');
                            ?>
                            <img class="mainnav__avatar img-sm rounded-circle border" src="{{ $uxx?->photo  }}" alt="Profile Picture">
                     </button>


                     <!-- User dropdown menu -->
                     <div class="dropdown-menu dropdown-menu-end">

                        <!-- User dropdown header -->
                        <div class="d-flex align-items-center border-bottom pb-3">
                           <div class="flex-grow-1">
                              <h3 class="mb-2">{{ auth()->user()->name }}</h3>
                              <span class="text-body-secondary fst-italic">{{ auth()->user()->email }}</span>
                           </div>
                        </div>

                        <a class="dropdown-item p-3" href="{{ route('profile') }}">Profile</a>
                        <form id="logout-form" method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a role="button" class="dropdown-item p-3" onclick="this.parentNode.submit();">
                                <span class="ms-1">Se déconnecter</span></a>
                        </form>

                     </div>
                  </div>
                  <!-- End - User dropdown -->

               </div>

@endsection
@section('navigation')
 <!-- Navigation Category -->
 <?php
    $active = \Illuminate\Support\Facades\Session::get('active');
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


@yield('modal')
@endsection
