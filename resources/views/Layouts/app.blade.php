<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-scheme="night" style="font-size:14px;">

<head>
    <meta name="generator" content="Hugo 0.87.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="description" content="Systeme de reporting de COGELO.">
    <title>@yield('title') | ANGARA</title>

    <!-- STYLESHEETS -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~--- -->
    <link rel="icon" type="image/svg" sizes="32x32" href="{{ asset('img/favicon.ico') }}">
    <!-- Fonts [ OPTIONAL ] -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

    <!-- Nifty CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/nifty.min.css') }}">

    <!-- Nifty Demo Icons [ OPTIONAL ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo-purpose/demo-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="{{ asset('css/jquill.css') }}" rel="stylesheet">

    <!-- Demo purpose CSS [ DEMO ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo-purpose/demo-settings.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/line-icons/premium-line-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/solid-icons/premium-solid-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/solid-icons/premium-line-icons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendors/loader.css/loader.min.css') }}">
    <script src="{{ asset('js/jquery.min.js') }}"></script>


</head>

<body class="out-quart" style="">

    <!-- PAGE CONTAINER -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <div id="root" class="root mn--min tm--fair-hd">

        <!-- CONTENTS -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <section id="content" class="content">
            <div class="content__header content__boxed overlapping">
                <div class="content__wrap">
                    @include('includes.flash-message')
                   <!-- Breadcrumb -->
                   <div class="d-flex justify-content-between">
                        @yield('breadcrumb')
                        @yield('actions')
                   </div>

                   <!-- END : Breadcrumb -->
                    @yield('page-header')
                </div>
             </div>
             <div class="content__boxed">
                <div class="content__wrap">
                    @yield('content')
                </div>
             </div>

            <!-- FOOTER -->
            <footer class="content__boxed mt-auto">
                <div class="content__wrap py-3 py-md-1 d-flex flex-column flex-md-row align-items-md-center">
                    <div class="text-nowrap mb-4 mb-md-0">Copyright &copy; {{ date('Y') }} <a href="#" class="ms-1 btn-link fw-bold">Alliages Technologies - Adjuvant</a></div>
                    <nav class="nav flex-column gap-1 flex-md-row gap-md-3 ms-md-auto" style="row-gap: 0 !important;">
                        <a class="nav-link px-0" href="#">Contactez nous</a>
                    </nav>
                </div>
            </footer>
            <!-- END - FOOTER -->

        </section>

        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - CONTENTS -->

        <!-- HEADER -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <header class="header">
            <div class="header__inner">

               <!-- Brand -->
               <div class="header__brand">
                  <div class="brand-wrap">

                     <!-- Brand logo -->
                     <a href="index.html" class="brand-img stretched-link">
                        <img style="width: 40px; height:40px; color:white" src="{{ asset('img/logo.png') }}" alt="Nifty Logo" class="Nifty logo mt-1" width="16" height="16">
                     </a>


                     <!-- Brand title -->
                     <div class="brand-title">ANGARA</div>
                  </div>
               </div>
               <!-- End - Brand -->


               <div class="header__content">

                  <!-- Content Header - Left Side: -->
                  <div class="header__content-start">


                     <!-- Navigation Toggler -->
                     <button type="button" class="nav-toggler header__btn btn btn-icon btn-sm" aria-label="Nav Toggler">
                        <i class="demo-psi-list-view"></i>
                     </button>

                     <div class="vr mx-1 d-none d-md-block"></div>

                     <!-- Searchbox -->
                     <div class="header-searchbox">

                        <!-- Searchbox toggler for small devices -->
                        <label for="header-search-input" class="header__btn d-md-none btn btn-icon rounded-pill shadow-none border-0 btn-sm" type="button">
                           <i class="demo-psi-magnifi-glass"></i>
                        </label>

                        <!-- Searchbox input -->
                        <form class="searchbox searchbox--auto-expand searchbox--hide-btn input-group">
                           <input id="header-search-input" class="searchbox__input form-control bg-transparent" type="search" placeholder="Rechercher ..."  oninput="onFilterTextBoxChanged()" aria-label="Search">
                           <div class="searchbox__backdrop">
                              <button class="searchbox__btn header__btn btn btn-icon rounded shadow-none border-0 btn-sm" type="button">
                                 <i class="demo-pli-magnifi-glass"></i>
                              </button>
                           </div>
                        </form>
                     </div>
                  </div>
                  <!-- End - Content Header - Left Side -->


                  <!-- Content Header - Right Side: -->
                  <div class="header__content-end">
                        @yield('top')
                  </div>
               </div>
            </div>
         </header>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - HEADER -->

        <!-- MAIN NAVIGATION -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <nav id="mainnav-container" class="mainnav">

            <div class="mainnav__inner">
                <!-- Navigation menu -->
                <div class="mainnav__top-content scrollable-content pb-5">

                    <!-- Profile Widget -->
                    <div class="mainnav__profile mt-3 d-flex3">

                        <div class="mt-2 d-mn-max"></div>

                        <!-- Profile picture  -->
                        <div class="mininav-toggle text-center py-2">
                            <img class="mainnav__avatar img-md rounded-circle border" src="{{ asset('assets/img/profile-photos/1.png') }}" alt="Profile Picture">
                        </div>

                        <div class="mininav-content collapse d-mn-max">
                            <div class="d-grid">

                                <!-- User name and position -->
                                <button class="d-block btn shadow-none p-2" data-bs-toggle="collapse" data-bs-target="#usernav" aria-expanded="false" aria-controls="usernav">
                                    <span class="dropdown-toggle d-flex justify-content-center align-items-center">
                                        <h6 class="mb-0 me-3">{{ auth()->user()->name }}</h6>
                                    </span>

                                </button>

                                <!-- Collapsed user menu -->
                                <div id="usernav" class="nav flex-column collapse">

                                    <a href="{{ route('profile') }}" class="nav-link">
                                        <i class="demo-pli-male fs-5 me-2"></i>
                                        <span class="ms-1">Profile</span>
                                    </a>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a role="button" class="nav-link" onclick="this.parentNode.submit();"><i class="demo-pli-unlock fs-5 me-2"></i><span class="ms-1">Se déconnecter</span></a>
                                    </form>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- End - Profile widget -->
     <!-- Navigation Category -->
     @yield('navigation')
     @yield('script')
     <!-- END : Navigation Category -->
 </div>
 <!-- End - Navigation menu -->

@include('includes.footer')
