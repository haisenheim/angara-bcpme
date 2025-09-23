@extends('Layouts.app')
@section('top')
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
<div>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}"><i class="pli-monitor-analytics fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tableau de board</span>
            </a>
        </li>
    </ul>
 </div>
 <div class="mainnav__categoriy py-3">
     <h6 class="mainnav__caption mt-0 px-3 fw-bold">Activite</h6>
     <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.entrees.create') }}" class="nav-link mininav-toggle {{ $active==2?'active':'' }}"><i class="pli-full-view fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Nouvelle entrée en stock</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.sorties.create') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}"><i class="pli-maximize fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Nouvelle sortie de stock</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.entrees.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}"><i class="pli-arrow-inside fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Historique des entrées</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.sorties.index') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}"><i class="pli-arrow-outside fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Historique des sorties</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.previsions.index') }}" class="nav-link mininav-toggle {{ $active==10?'active':'' }}"><i class="pli-calendar fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Previsions</span>
            </a>
        </li>


     </ul>
 </div>
 <!-- END : Navigation Category -->

 <div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">Paiements</h6>
    <ul class="mainnav__menu nav flex-column">

       <li class="nav-item">
           <a href="{{ route('admin.requests.create') }}" class="nav-link mininav-toggle {{ $active==6?'active':'' }}"><i class="pli-bell fs-3 me-2"></i>
               <span class="nav-label mininav-content ms-1">Nouvel appel de fonds</span>
           </a>
       </li>

       <li class="nav-item">
            <a href="{{ route('admin.requests.index') }}" class="nav-link mininav-toggle {{ $active==7?'active':'' }}"><i class="pli-coins-3 fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Tous les appels de fonds</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.my.wallets') }}" class="nav-link mininav-toggle {{ $active==8?'active':'' }}"><i class="pli-wallet fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Mes Wallets</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.caisses.index') }}" class="nav-link mininav-toggle {{ $active==9?'active':'' }}"><i class="pli-atm fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Mes Caisses</span>
            </a>
        </li>
        

        <li class="nav-item">
            <a href="#" class="nav-link mininav-toggle {{ $active==18?'active':'' }}"><i class="pli-coins fs-3 me-2"></i>
                <span class="nav-label mininav-content ms-1">Paiements producteurs</span>
            </a>
        </li>



    </ul>
</div>

<div class="mainnav__categoriy py-3">
    <h6 class="mainnav__caption mt-0 px-3 fw-bold">Systeme</h6>
    <ul class="mainnav__menu nav flex-column">
        <li class="nav-item">
            <a href="{{ route('admin.members.index') }}" class="nav-link mininav-toggle {{ $active==9?'active':'' }}"><i class="pli-farmer fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Membres</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.agents.index') }}" class="nav-link mininav-toggle {{ $active==10?'active':'' }}"><i class="pli-worker fs-2 me-2"></i>
                <span class="nav-label mininav-content ms-1">Producteurs relais</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link mininav-toggle {{ $active==6?'active':'' }}">
                {{-- <i class="pli-conference fs-2 me-2"></i> --}}
                <span class="icon-nav"><img src="{{ asset('img/new/navigation/utilisateurs.svg')}}" alt=""></span>
                <span class="nav-label mininav-content ms-1">Utilisateurs</span>
            </a>
        </li>
        <!-- Link with submenu -->
       <li class="nav-item has-sub">
           <a href="#" class="mininav-toggle nav-link {{ ($active>700&&$active<800)?'active':'' }}"><i class="pli-gear fs-2 me-2"></i>
               <span class="nav-label ms-1">Parametres</span>
           </a>
           <!-- Settings submenu list -->
           <ul class="mininav-content nav collapse">

              <li class="nav-item">
                  <a href="{{ route('admin.entrepots.index') }}" class="nav-link {{ $active==701?'active':'' }}">Entrepots</a>
              </li>
              <li class="nav-item">
                  <a href="{{ route('admin.villages.index') }}" class="nav-link {{ $active==702?'active':'' }}">Villages</a>
              </li>
           </ul>
           <!-- END : Dashboard submenu list -->
       </li>
       <!-- END : Link with submenu -->

    </ul>
</div>
@endsection
