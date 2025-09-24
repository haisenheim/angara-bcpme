@extends('Layouts.app')
@section('top')

    {{-- <div>
        <span>Connecté  en tant que :</span>
        <strong><span class="badge bg-white text-dark fs-6">Administrateur</span></strong>
    </div> --}}
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

                        {{-- <div class="row">
                           <div class="col-md-7">

                              <!-- Simple widget and reports -->
                              <div class="list-group list-group-borderless mb-3">
                                 <div class="list-group-item text-center border-bottom mb-3">
                                    <p class="h1 display-1 text-primary fw-semibold">17</p>
                                    <p class="h6 mb-0"><i class="demo-pli-basket-coins fs-3 me-2"></i> New orders</p>
                                    <small class="text-body-secondary">You have new orders</small>
                                 </div>
                                 <div class="list-group-item py-0 d-flex justify-content-between align-items-center">
                                    Today Earning
                                    <small class="fw-bolder">$578</small>
                                 </div>
                                 <div class="list-group-item py-0 d-flex justify-content-between align-items-center">
                                    Tax
                                    <small class="fw-bolder text-danger">- $28</small>
                                 </div>
                                 <div class="list-group-item py-0 d-flex justify-content-between align-items-center">
                                    Total Earning
                                    <span class="fw-bolder text-body-emphasis">$6,578</span>
                                 </div>
                              </div>


                           </div>
                           <div class="col-md-5">

                              <!-- User menu link -->
                              <div class="list-group list-group-borderless h-100 py-3">
                                 <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span><i class="demo-pli-mail fs-5 me-2"></i> Messages</span>
                                    <span class="badge bg-danger rounded-pill">14</span>
                                 </a>
                                 <a href="#" class="list-group-item list-group-item-action">
                                    <i class="demo-pli-male fs-5 me-2"></i> Profile
                                 </a>
                                 <a href="#" class="list-group-item list-group-item-action">
                                    <i class="demo-pli-gear fs-5 me-2"></i> Settings
                                 </a>

                                 <a href="#" class="list-group-item list-group-item-action mt-auto">
                                    <i class="demo-pli-computer-secure fs-5 me-2"></i> Lock screen
                                 </a>
                                 <a href="#" class="list-group-item list-group-item-action">
                                    <i class="demo-pli-unlock fs-5 me-2"></i> Logout
                                 </a>
                              </div>


                           </div>
                        </div> --}}

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
     <!-- Navigation Category -->
     <div class="mainnav__categoriy py-3">
        <ul class="mainnav__menu nav flex-column gap-2">
           <li class="nav-item">
               <a href="{{ route('admin.dashboard') }}" class="nav-link mininav-toggle {{ $active==1?'active':'' }}">
                    {{-- <i class="demo-pli-home fs-3 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/dashboard.svg')}}" alt=""></span>
                   <span class="nav-label mininav-content ms-1">Tableau de board</span>
               </a>
           </li>


        <!-- Link with submenu -->
         <li class="nav-item has-sub">
            <a href="#" class="mininav-toggle nav-link {{ ($active>200&&$active<300)?'active':'' }}">
                {{-- <i class="pli-folders fs-5 me-2"></i> --}}
                <span class="icon-nav"><img src="{{ asset('img/new/navigation/dossiers.svg')}}" alt=""></span>
                <span class="nav-label ms-1">DOSSIERS</span>
            </a>
            <!-- Settings submenu list -->
            <ul class="mininav-content nav collapse">
                <li class="nav-item">
                    <a href="{{ route('admin.dossiers.index') }}" class="nav-link {{ $active==201?'active':'' }}">DOSSIERS D'INSTRUCTION</a>
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
    <!-- END : Navigation Category -->

    <div class="mainnav__categoriy py-3">
        <h6 class="mainnav__caption mt-0 px-3 fw-bold">PORTEFEUILLE</h6>
        <ul class="mainnav__menu nav flex-column">
            <li class="nav-item">
                <a href="{{ route('admin.entreprises.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}">
                    {{-- <i class="pli-bank fs-2 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/enterprises.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">ENTREPRISES</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.entites.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}">
                    {{-- <i class="pli-bank fs-2 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/enterprises.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">ENTITES INDIV.</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.cooperatives.index') }}" class="nav-link mininav-toggle {{ $active==4?'active':'' }}">
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/programmes.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">ORGANISAT. INTERM.</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.programmes.index') }}" class="nav-link mininav-toggle {{ $active==3?'active':'' }}">
                    {{-- <i class="pli-affiliate fs-2 me-2"></i> --}}
                    <span  class="icon-nav" class="icon-nav"><img src="{{ asset('img/new/navigation/programmes.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">PROGRAMMES</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.entreprises.prospects') }}" class="nav-link mininav-toggle {{ $active==5?'active':'' }}">
                    {{-- <i class="pli-phone-2 fs-2 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/prospect.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">PROSPECTS</span>
                </a>
            </li>
        </ul>
    </div>

         <!-- Navigation Category -->
    <div class="mainnav__categoriy py-3">
        <h6 class="mainnav__caption mt-0 px-3 fw-bold">RESEAU</h6>
        <ul class="mainnav__menu nav flex-column gap-2">
           <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link mininav-toggle {{ $active==6?'active':'' }}">
                    {{-- <i class="pli-conference fs-2 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/utilisateurs.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">Utilisateurs</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.secteurs.index') }}" class="nav-link mininav-toggle {{ $active==7?'active':'' }}">
                    {{-- <i class="pli-conference fs-2 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/territoire.svg')}}" alt=""></span>
                    <span class="nav-label mininav-content ms-1">SECTEURS COOP.</span>
                </a>
            </li>

         <!-- Link with submenu -->
         <li class="nav-item has-sub">
             <a href="#" class="mininav-toggle nav-link {{ ($active>700&&$active<800)?'active':'' }}">
                {{-- <i class="pli-map fs-5 me-2"></i> --}}
                <span class="icon-nav"><img src="{{ asset('img/new/navigation/territoire.svg')}}" alt=""></span>
                 <span class="nav-label ms-1">Territoire</span>
             </a>
             <!-- Settings submenu list -->
             <ul class="mininav-content nav collapse">
                 <li class="nav-item">
                     <a href="{{ route('admin.territoire') }}" class="nav-link {{ $active==701?'active':'' }}">Organisation administrative</a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('admin.agences.index') }}" class="nav-link {{ $active==702?'active':'' }}">Agences</a>
                 </li>
                 <li class="nav-item">
                     <a href="{{ route('admin.villages.index') }}" class="nav-link {{ $active==703?'active':'' }}">Villages</a>
                 </li>

             </ul>
             <!-- END : Dashboard submenu list -->
         </li>
         <!-- END : Link with submenu -->
            <!-- Link with submenu -->
            <li class="nav-item has-sub">
                <a href="#" class="mininav-toggle nav-link {{ ($active>800&&$active<900)?'active':'' }}">
                    {{-- <i class="demo-pli-gears fs-5 me-2"></i> --}}
                    <span class="icon-nav"><img src="{{ asset('img/new/navigation/parametres.svg')}}" alt=""></span>
                    <span class="nav-label ms-1">Parametres</span>
                </a>
                <!-- Settings submenu list -->
                <ul class="mininav-content nav collapse">
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $active==801?'active':'' }}">Organismes</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link {{ $active==802?'active':'' }}">Banques</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.operateurs.index') }}" class="nav-link {{ $active==803?'active':'' }}">Operateurs mobiles</a>
                    </li>
                </ul>
                <!-- END : Dashboard submenu list -->
            </li>
            <!-- END : Link with submenu -->

        </ul>
    </div>
    <!-- END : Navigation Category -->
@endsection
