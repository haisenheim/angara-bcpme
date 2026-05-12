<!DOCTYPE html>
<html lang="fr" data-bs-theme="light" data-scheme="corn" style="font-size:14px;">

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
    <meta name="description" content="Angara — plateforme financière. Déploiement Banque Camerounaise des Petites et Moyennes Entreprises (BC-PME).">
    <title>ANGARA | @yield('title')</title>
    <!-- STYLESHEETS -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <!-- Fonts [ OPTIONAL ] -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;family=Open+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">

    <!-- Nifty CSS [ REQUIRED ] -->
    <link rel="stylesheet" href="{{ asset('assets/css/nifty.css') }}">

       <!-- Nifty Demo Icons [ OPTIONAL ] -->
       <link rel="stylesheet" href="{{ asset('assets/css/demo-purpose/demo-icons.min.css') }}">
       <link rel="stylesheet" href="{{ asset('css/style.css') }}">
       <link href="{{ asset('css/jquill.css') }}" rel="stylesheet">

        <!-- Angara Custom Style -->
        <link rel="stylesheet" href="{{ asset('css/angara-style.css') }}">
        <link rel="stylesheet" href="{{ asset('css/nifty-override.css') }}">
        <link rel="stylesheet" href="{{ asset('css/angara-table.css') }}">

        <!-- Demo purpose CSS [ DEMO ] -->
        <link rel="stylesheet" href="{{ asset('assets/css/demo-purpose/demo-settings.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/line-icons/premium-line-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/solid-icons/premium-solid-icons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/premium/icon-sets/solid-icons/premium-line-icons.min.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/vendors/loader.css/loader.min.css') }}">
        <script src="{{ asset('js/jquery.min.js') }}"></script>
        <script src="{{ asset('js/quill.min.js') }}"></script>
        <script src="{{ asset('js/angara-table.js') }}" defer></script>

        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Favicons [ OPTIONAL ] -->
        <link rel="apple-touch-icon" sizes="180x180" href="./apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="./favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="./favicon-16x16.png">
        <link rel="manifest" href="./site.webmanifest">

        @stack('styles')

 </head>

<body class="out-quart" style="">

    <!-- PAGE CONTAINER -->
    <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
    <div id="root" class="root mn--min tm--fair-hd hd--sticky mn--sticky">

        <!-- CONTENTS -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <section id="content" class="content">
            <div class="content__header content__boxed overlapping">
                <div class="content__wrap">
                    @include('includes.flash-message')
                   <!-- Breadcrumb -->
                   <div class="d-flex justify-content-between flex-column flex-md-row align-items-start gap-2 gap-md-3">
                        @yield('breadcrumb')
                        <div class="angara-page-actions ms-md-auto">
                            @yield('actions')
                        </div>
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
            <footer class="content__boxed mt-auto bc-pme-footer">
                <div class="content__wrap py-3 py-md-1 d-flex flex-column flex-md-row align-items-md-center">
                    <div class="text-nowrap mb-4 mb-md-0">Copyright &copy; {{ date('Y') }} <a href="#" class="ms-1 btn-link fw-bold">Angara finance</a></div>
                    <nav class="nav flex-column gap-1 flex-md-row gap-md-3 ms-md-auto" style="row-gap: 0 !important;">
                        @auth
                            @if (Route::has('document-templates.index'))
                                <a class="nav-link px-0" href="{{ route('document-templates.index') }}">Modèles de documents</a>
                            @endif
                        @endauth
                        <a class="nav-link px-0" href="#">Contactez-nous</a>
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
                     <a href="#" class="brand-img stretched-link">
                        <img src="{{ asset('img/logo-bcpme.png') }}" alt="Banque Camerounaise des Petites et Moyennes Entreprises — Angara" class="Nifty logo" >
                     </a>

                     <!-- Icon logo (mode réduit) -->
                     <a href="#" class="brand-icon stretched-link">
                        <img src="{{ asset('img/logo-bcpme.png') }}" alt="BC-PME" >
                     </a>
                  </div>
               </div>
               <!-- End - Brand -->


               <div class="header__content">

                  <!-- Content Header - Left Side: -->
                  <div class="header__content-start">


                     <!-- Navigation Toggler -->
                     <button type="button" class="nav-toggler header__btn btn btn-icon btn-sm" aria-label="Nav Toggler">
                        <i class="bi bi-list"></i>
                     </button>

                     <div class="vr mx-1 d-none d-md-block"></div>

                     <!-- Searchbox -->
                     <div class="header-searchbox">

                        <!-- Searchbox toggler for small devices -->
                        <label for="header-search-input" class="header__btn d-md-none btn btn-icon rounded shadow-none border-0 btn-sm" type="button">
                           <i class="bi bi-search"></i>
                        </label>

                        <!-- Searchbox input -->
                        <form class="searchbox searchbox--auto-expand searchbox--hide-btn input-group">
                           <input id="header-search-input" class="searchbox__input form-control bg-transparent" type="search" placeholder="Rechercher ..."  oninput="onFilterTextBoxChanged()" aria-label="Search">
                           <div class="searchbox__backdrop">
                              <button class="searchbox__btn header__btn btn btn-icon rounded shadow-none border-0 btn-sm" type="button">
                                 <i class="bi bi-search"></i>
                              </button>
                           </div>
                        </form>
                     </div>
                  </div>
                  <!-- End - Content Header - Left Side -->


                  <!-- Content Header - Right Side: -->

                @yield('top')

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

                        <!-- Profile picture  -->
                        {{-- <div class="mininav-toggle text-center py-2">
                            <?php
                                $uxx = \Illuminate\Support\Facades\Session::get('user');
                            ?>
                            <img class="mainnav__avatar img-md rounded-circle border" src="{{ $uxx?->photo  }}" alt="Profile Picture">
                        </div> --}}

                        {{-- <div class="mininav-content collapse d-mn-max">
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
                                        <i class="bi bi-person-circle fs-5 me-2"></i>
                                        <span class="ms-1">Profile</span>
                                    </a>
                                    <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a role="button" class="nav-link" onclick="this.parentNode.submit();"><i class="bi bi-box-arrow-right fs-5 me-2"></i><span class="ms-1">Se déconnecter</span></a>
                                    </form>
                                </div>

                            </div>
                        </div> --}}

                    </div>
                    <!-- End - Profile widget -->
     <!-- Navigation Category -->
     @yield('navigation')
     @yield('script')
     <!-- END : Navigation Category -->
 </div>
 <!-- End - Navigation menu -->

@include('includes.footer')
