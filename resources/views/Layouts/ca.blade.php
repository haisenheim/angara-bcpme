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
                <strong><span class="badge bg-white text-dark fs-6">Chef d'agence</span></strong>
            </div>
        </div>
    </div>
    <div class="header__content-end">
                  <!-- Notification Dropdown -->
                  <div class="dropdown">

                     <!-- Toggler -->
                     <button class="header__btn btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-label="Notification dropdown" aria-expanded="false">
                        <span class="d-block position-relative">
                           <i class="demo-psi-bell"></i>

                           <span class="badge badge-super rounded-pill bg-danger p-1">
                              <span class="visually-hidden">unread messages</span>
                           </span>

                        </span>
                     </button>


                     <!-- Notification dropdown menu -->
                     <div class="dropdown-menu dropdown-menu-end w-md-300px">
                        <div class="border-bottom px-3 py-2 mb-3">
                           <h5>Notifications</h5>
                        </div>
                        <div class="list-group list-group-borderless">


                           <!-- List item -->
                           <div class="list-group-item list-group-item-action d-flex align-items-center mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <i class="demo-psi-data-settings text-danger fs-2"></i>
                              </div>
                              <div class="flex-grow-1">
                                 <a href="#" class="h6 fw-normal d-block mb-0 stretched-link text-decoration-none">Your storage is full</a>
                                 <small class="text-body-secondary">Local storage is nearly full.</small>
                              </div>
                           </div>


                           <!-- List item -->
                           <div class="list-group-item list-group-item-action d-flex align-items-center mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <i class="demo-psi-pen-5 text-info fs-2"></i>
                              </div>
                              <div class="flex-grow-1">
                                 <a href="#" class="h6 fw-normal d-block mb-0 stretched-link text-decoration-none">Writing a New Article</a>
                                 <small class="text-body-secondary">Wrote a news article for the John Mike</small>
                              </div>
                           </div>


                           <!-- List item -->
                           <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <i class="demo-psi-speech-bubble-3 text-success fs-2"></i>
                              </div>
                              <div class="flex-grow-1">
                                 <div class="d-flex justify-content-between align-items-start">
                                    <a href="#" class="h6 fw-normal mb-0 stretched-link text-decoration-none">Comment sorting</a>
                                    <span class="badge bg-info rounded ms-auto">NEW</span>
                                 </div>
                                 <small class="text-body-secondary">You have 1,256 unsorted comments.</small>
                              </div>
                           </div>


                           <!-- List item -->
                           <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <img class="img-xs rounded-circle" src="../../assets/img/profile-photos/7.png" alt="Profile Picture" loading="lazy">
                              </div>
                              <div class="flex-grow-1">
                                 <a href="#" class="h6 fw-normal d-block mb-0 stretched-link text-decoration-none">Lucy Sent you a message</a>
                                 <small class="text-body-secondary">30 minutes ago</small>
                              </div>
                           </div>


                           <!-- List item -->
                           <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                              <div class="flex-shrink-0 me-3">
                                 <img class="img-xs rounded-circle" src="../../assets/img/profile-photos/3.png" alt="Profile Picture" loading="lazy">
                              </div>
                              <div class="flex-grow-1">
                                 <a href="#" class="h6 fw-normal d-block mb-0 stretched-link text-decoration-none">Jackson Sent you a message</a>
                                 <small class="text-body-secondary">1 hours ago</small>
                              </div>
                           </div>

                           <div class="text-center mb-2">
                              <a href="#" class="btn-link text-primary icon-link icon-link-hover">
                                 Show all Notifications
                                 <i class="bi demo-psi-arrow-out-right"></i>
                              </a>
                           </div>

                        </div>
                     </div>
                  </div>
                  <!-- End - Notification dropdown -->


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
           <a href="{{ route('ca.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="demo-pli-home fs-3 me-2"></i>
               <span class="nav-label mininav-content ms-1">Accueil</span>
           </a>
       </li>

    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">INTERMEDIATION</h6>
    <ul class="mainnav__menu nav flex-column">

        <li class="nav-item">
            <a href="{{ route('ca.dossiers.index') }}" class="nav-link mininav-toggle {{ $active==201?'active':'' }}"><i class="pli-folder fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">INSTRUCTION</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link mininav-toggle {{ $active==202?'active':'' }}"><i class="pli-folders fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">COMPENSATION</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ $active==203?'active':'' }}"><i class="pli-files fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">INVESTISSEMENT</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link {{ $active==204?'active':'' }}"><i class="pli-handshake fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">GARANTIE</span>
            </a>
        </li>
    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
    <ul class="mainnav__menu nav flex-column">
       <li class="nav-item">
           <a href="{{ route('ca.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==401?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
               <span class="nav-label mininav-content ms-1">Entreprises</span>
           </a>
       </li>
       <li class="nav-item">
           <a href="{{ route('ca.cooperatives.index') }}" class="nav-link mininav-toggle {{ $active==402?'active':'' }}"><i class="pli-leafs fs-3 me-2"></i>
               <span class="nav-label mininav-content ms-1">Organisations interméd.</span>
           </a>
       </li>
       <li class="nav-item">
           <a href="{{ route('ca.entites.index') }}" class="nav-link mininav-toggle {{ $active==403?'active':'' }}"><i class="pli-bank fs-2 me-2"></i>
               <span class="nav-label mininav-content ms-1">Entités individuelles</span>
           </a>
       </li>
       <li class="nav-item">
            <a href="{{ route('ca.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==404?'active':'' }}"><i class="pli-phone-2 fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Prospects</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ca.programmes.index') }}" class="nav-link mininav-toggle {{ $active==405?'active':'' }}"><i class="pli-affiliate fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">programmes</span>
            </a>
        </li>

    </ul>
</div>
<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">STRUCTURATION</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('ca.secteurs.index') }}" class="nav-link mininav-toggle {{ $active==10?'active':'' }}"><i class="pli-map fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">SECTEURS COOP.</span>
            </a>
        </li>
    </ul>
</div>

<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">Systeme</h6>
    <ul class="mainnav__menu nav flex-column">

        <li class="nav-item">
            <a href="{{ route('ca.users.index') }}" class="nav-link mininav-toggle {{ $active==11?'active':'' }}"><i class="pli-conference fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Utilisateurs</span>
            </a>
        </li>
    </ul>
</div>
@endsection
