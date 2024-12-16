<template>
    <div>
    <!-- PAGE CONTAINER -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
     <div id="root" :class="!max?'mn--max':'mn--min'" class="root hd--expanded">
        <!-- CONTENTS -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <section id="content" class="content">
            <router-view></router-view>
            <!-- FOOTER -->
            <footer class="mt-auto">
                <div class="content__boxed">
                    <div class="content__wrap py-3 py-md-1 d-flex flex-column flex-md-row align-items-md-center">
                        <div class="text-nowrap mb-4 mb-md-0">Copyright &copy; {{ date }} <a href="#" class="ms-1 btn-link fw-bold">SKULU</a></div>
                        <nav class="nav flex-column gap-1 flex-md-row gap-md-3 ms-md-auto" style="row-gap: 0 !important;">
                            <a class="nav-link px-0" href="#">Policy Privacy</a>
                            <a class="nav-link px-0" href="#">Conditions d'utilisation</a>
                            <router-link class="nav-link px-0" to="/contact">Contactez nous</router-link>
                        </nav>
                    </div>
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
                            <img :src="logo" :alt="tenant.name" class="Nifty logo" width="40" height="40">
                        </a>

                        <!-- Brand title -->
                        <div class="brand-title">{{ tenant.name }}</div>

                        <!-- You can also use IMG or SVG instead of a text element. -->

                    </div>
                </div>
                <!-- End - Brand -->

                <div class="header__content">

                    <!-- Content Header - Left Side: -->
                    <div class="header__content-start">

                        <!-- Navigation Toggler -->
                        <button @click="toggle()" type="button" class="nav-toggler header__btn btn btn-icon btn-sm" aria-label="Nav Toggler">
                            <i class="demo-psi-view-list"></i>
                        </button>

                        <!-- Searchbox -->
                        <div class="header-searchbox">

                            <!-- Searchbox toggler for small devices -->
                            <label for="header-search-input" class="header__btn d-md-none btn btn-icon rounded-pill shadow-none border-0 btn-sm" type="button">
                                <i class="demo-psi-magnifi-glass"></i>
                            </label>

                            <!-- Searchbox input -->
                            <form class="searchbox searchbox--auto-expand searchbox--hide-btn input-group">
                                <input id="header-search-input" class="searchbox__input form-control bg-transparent" type="search" placeholder="Rechercher . . ." aria-label="Search">
                                <div class="searchbox__backdrop">
                                    <button class="searchbox__btn header__btn btn btn-icon rounded shadow-none border-0 btn-sm" type="button" id="button-addon2">
                                        <i class="demo-pli-magnifi-glass"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End - Content Header - Left Side -->

                    <!-- Content Header - Right Side: -->
                    <div class="header__content-end">



                        <!-- Notification Dropdown -->
                        <div class="dropdown">

                            <!-- Toggler -->
                            <button class="header__btn btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-label="Notification dropdown" aria-expanded="false">
                                <span class="d-block position-relative">
                                    <i class="demo-psi-bell"></i>
                                    <span class="badge badge-super rounded bg-danger p-1">

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
                                    <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="demo-psi-data-settings text-muted fs-2"></i>
                                        </div>
                                        <div class="flex-grow-1 ">
                                            <a href="#" class="h6 d-block mb-0 stretched-link text-decoration-none">Your storage is full</a>
                                            <small class="text-muted">Local storage is nearly full.</small>
                                        </div>
                                    </div>

                                    <!-- List item -->
                                    <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="demo-psi-file-edit text-blue-200 fs-2"></i>
                                        </div>
                                        <div class="flex-grow-1 ">
                                            <a href="#" class="h6 d-block mb-0 stretched-link text-decoration-none">Writing a New Article</a>
                                            <small class="text-muted">Wrote a news article for the John Mike</small>
                                        </div>
                                    </div>

                                    <!-- List item -->
                                    <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="demo-psi-speech-bubble-7 text-green-300 fs-2"></i>
                                        </div>
                                        <div class="flex-grow-1 ">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <a href="#" class="h6 mb-0 stretched-link text-decoration-none">Comment sorting</a>
                                                <span class="badge bg-info rounded ms-auto">NEW</span>
                                            </div>
                                            <small class="text-muted">You have 1,256 unsorted comments.</small>
                                        </div>
                                    </div>



                                    <div class="text-center mb-2">
                                        <a href="#" class="btn-link">Show all Notifications</a>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End - Notification dropdown -->

                        <!-- User dropdown -->
                        <div class="dropdown">

                            <!-- Toggler -->
                            <button class="header__btn btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-label="User dropdown" aria-expanded="false">
                                <i class="demo-psi-male"></i>
                            </button>

                            <!-- User dropdown menu -->
                            <div class="dropdown-menu dropdown-menu-end w-md-450px">

                                <!-- User dropdown header -->
                                <div class="d-flex align-items-center border-bottom px-3 py-2">
                                    <div class="flex-shrink-0">

                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ username }}</h5>
                                        <span class="text-muted fst-italic">aaron_chavez@example.com</span>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-md-5">

                                        <!-- User menu link -->
                                        <div class="list-group list-group-borderless h-100 py-3">

                                            <a href="#" class="list-group-item list-group-item-action">
                                                <i class="demo-pli-male fs-5 me-2"></i> Mon Profil
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">
                                                <i class="demo-pli-gear fs-5 me-2"></i> Parametres
                                            </a>

                                            <a href="#" class="list-group-item list-group-item-action">
                                                <i class="demo-pli-unlock fs-5 me-2"></i> Se deconnecter
                                            </a>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- End - User dropdown -->
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

                <AdminNavbar></AdminNavbar>
                <!-- Bottom navigation menu -->
                <div class="mainnav__bottom-content border-top pb-2">
                    <ul id="mainnav" class="mainnav__menu nav flex-column">
                        <li class="nav-item has-sub">
                            <a href="#" class="nav-link mininav-toggle collapsed" aria-expanded="false">
                                <i class="demo-pli-unlock fs-5 me-2"></i>
                                <span class="nav-label ms-1">Se deconnecter</span>
                            </a>
                            <ul class="mininav-content nav flex-column collapse">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">This device only</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">All Devices</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Lock screen</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- End - Bottom navigation menu -->

            </div>
        </nav>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - MAIN NAVIGATION -->

        </div>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - PAGE CONTAINER -->

        <!-- SCROLL TO TOP BUTTON -->
        <div class="scroll-container">
        <a href="#root" class="scroll-page rounded-circle ratio ratio-1x1" aria-label="Scroll button"></a>
        </div>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - SCROLL TO TOP BUTTON -->
    </div>
</template>
<script>
    import AdminNavbar from '@/Components/Navbar/Tenant/Admin.vue';
    import img from '~/assets/img/logo.svg';
    export default{
        name:'BackendLayoutVue',
        components:{
            AdminNavbar,
        },
        computed:{
            isAuthenticated(){
                return this.$store.state.authenticated;
            },
            username(){
                return this.$store.state.user.name;
            }
        },
        data: function(){
            return {
                authenticated:false,
                logo:img,
                max:true,
                tenant:this.$store.state.tenant,
                date:new Date().getFullYear()
            }
        },
        methods:{
            logout(){
                this.$store.commit('SET_AUTHENTICATED',false);
                this.$emit('logout');
            },
            toggle(){
                this.$store.state.navbar_min;
                this.max=!this.max;
                this.$store.commit('SET_NAVBAR_MIN',!this.max);
            }

        }
    }
</script>
