<template>
    <div>
    <!-- PAGE CONTAINER -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
     <div @click="hideSb()" id="root" class="root mn--min hd--expanded">
        <!-- CONTENTS -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <section id="content" class="content">
            <router-view></router-view>
            <!-- FOOTER -->
            <footer class="mt-auto">
                <div class="content__boxed">
                    <div class="content__wrap py-3 py-md-1 d-flex flex-column flex-md-row align-items-md-center">
                        <div class="text-nowrap mb-4 mb-md-0">Copyright &copy; {{ date }} <a href="#" class="ms-1 btn-link fw-bold">DGC System - By Alliages Technologies</a></div>
                        <nav class="nav flex-column gap-1 flex-md-row gap-md-3 ms-md-auto" style="row-gap: 0 !important;">
                            <a class="nav-link px-0" href="#">Conditions d'utilisation</a>
                            <router-link class="nav-link px-0" to="#">Contactez nous</router-link>
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
                            <img :src="logo" alt="Nifty Logo" style="background-color: #fff;" class="Nifty logo rounded-circle" width="100" height="100">
                        </a>

                        <!-- Brand title -->
                        <div class="brand-title">SKULSUP</div>

                        <!-- You can also use IMG or SVG instead of a text element. -->

                    </div>
                </div>
                <!-- End - Brand -->

                <div class="header__content">

                    <!-- Content Header - Left Side: -->
                    <div class="header__content-start">

                        <!-- Navigation Toggler -->
                        <button type="button" class="nav-toggler header__btn btn btn-icon btn-sm" aria-label="Nav Toggler">
                            <i class="demo-psi-view-list"></i>
                        </button>

                        <!-- Searchbox -->
                        <div class="header-searchbox">

                            <!-- Searchbox toggler for small devices -->
                            <label for="header-search-input" class="header__btn d-md-none btn btn-icon rounded-pill shadow-none border-0 btn-sm" type="button">
                                <i class="demo-psi-magnifi-glass"></i>
                            </label>

                            <!-- Searchbox input -->
                            <form v-if="search" class="searchbox searchbox--auto-expand searchbox--hide-btn input-group">
                                <input id="filter-text-box" v-on:input="onFilterTextBoxChanged()"  class="searchbox__input form-control bg-transparent" type="search" placeholder="Saisir pour rechercher . . ." aria-label="Search">
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

                        <!-- Mega Dropdown -->
                        <div class="dropdown">

                            <!-- Toggler -->
                            <button class="header__btn btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-label="Megamenu dropdown" aria-expanded="false">
                                <i class="demo-psi-layout-grid"></i>
                            </button>

                            <!-- Mega Dropdown Menu -->
                            <div class="dropdown-menu dropdown-menu-end p-3 mega-dropdown">
                                <div class="row">
                                    <div class="col-md-3">

                                        <!-- Pages List Group -->
                                        <div class="list-group list-group-borderless">
                                            <div class="list-group-item d-flex align-items-center border-bottom mb-2">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="demo-pli-file fs-4"></i>
                                                </div>
                                                <h5 class="flex-grow-1 m-0">Pages</h5>
                                            </div>
                                            <a href="#" class="list-group-item list-group-item-action">Profile</a>
                                            <a href="#" class="list-group-item list-group-item-action">Search Result</a>
                                            <a href="#" class="list-group-item list-group-item-action">FAQ</a>
                                            <a href="#" class="list-group-item list-group-item-action">Screen Lock</a>
                                            <a href="#" class="list-group-item list-group-item-action">Maintenance</a>
                                            <a href="#" class="list-group-item list-group-item-action">Invoices</a>
                                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">Disabled Item</a>
                                        </div>

                                    </div>
                                    <div class="col-md-3">

                                        <!-- Mailbox list group -->
                                        <div class="list-group list-group-borderless mb-3">
                                            <div class="list-group-item d-flex align-items-center border-bottom mb-2">
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="demo-pli-mail fs-4"></i>
                                                </div>
                                                <h5 class="flex-grow-1 m-0">Mailbox</h5>
                                            </div>
                                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                Inbox <span class="badge bg-danger rounded-pill">14</span>
                                            </a>
                                            <a href="#" class="list-group-item list-group-item-action">Read Messages</a>
                                            <a href="#" class="list-group-item list-group-item-action">Compose</a>
                                            <a href="#" class="list-group-item list-group-item-action">Template</a>
                                        </div>



                                    </div>
                                    <div class="col-md-3">

                                        <!-- List Group -->
                                        <div class="list-group list-group-borderless">
                                            <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="demo-pli-data-settings fs-2"></i>
                                                </div>
                                                <div class="flex-grow-1 ">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <a href="#" class="h6 d-block mb-1 stretched-link text-decoration-none">Data Backup</a>
                                                        <span class="badge bg-success rounded-pill ms-auto">40%</span>
                                                    </div>
                                                    <small class="text-muted">Current usage of disks for backups.</small>
                                                </div>
                                            </div>

                                            <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="demo-pli-support fs-2"></i>
                                                </div>
                                                <div class="flex-grow-1 ">
                                                    <a href="#" class="h6 d-block mb-1 stretched-link text-decoration-none">Support</a>
                                                    <small class="text-muted">Have any questions ? please don't hesitate to ask.</small>
                                                </div>
                                            </div>

                                            <div class="list-group-item list-group-item-action d-flex align-items-start mb-3">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="demo-pli-computer-secure fs-2"></i>
                                                </div>
                                                <div class="flex-grow-1 ">
                                                    <a href="#" class="h6 d-block mb-1 stretched-link text-decoration-none">Security</a>
                                                    <small class="text-muted">Our devices are secure and up-to-date.</small>
                                                </div>
                                            </div>

                                            <div class="list-group-item list-group-item-action d-flex align-items-start">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="demo-pli-map-2 fs-2"></i>
                                                </div>
                                                <div class="flex-grow-1 ">
                                                    <a href="#" class="h6 d-block mb-1 stretched-link text-decoration-none">Location</a>
                                                    <small class="text-muted">From our location up here, we kept in close touch.</small>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-3">



                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End - Mega Dropdown -->


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
                                        <h5 class="mb-0">Aaron Chavez</h5>
                                        <span class="text-muted fst-italic">aaron_chavez@example.com</span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7">

                                        <!-- Simple widget and reports -->
                                        <div class="list-group list-group-borderless mb-3">
                                            <div class="list-group-item text-center border-bottom mb-3">
                                                <p class="h1 display-1 text-green">17</p>
                                                <p class="h6 mb-0"><i class="demo-pli-basket-coins fs-3 me-2"></i> New orders</p>
                                                <small class="text-muted">You have new orders</small>
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
                                                <span class="fw-bold text-primary">$6,578</span>
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
                                </div>

                            </div>
                        </div>
                        <!-- End - User dropdown -->

                        <!-- Sidebar Toggler -->
                        <button v-if="sb" @click.stop="showSb()" class="sidebar-toggler header__btn btn btn-icon btn-sm" type="button" aria-label="Sidebar button">
                            <i class="demo-psi-dot-vertical"></i>
                        </button>
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
                <SuperNavbar v-if="roleId==1"></SuperNavbar>
                <TeacherNavbar v-if="roleId==2"></TeacherNavbar>
                <!-- Bottom navigation menu -->
                <div class="mainnav__bottom-content border-top pb-2">
                    <ul id="mainnav" class="mainnav__menu nav flex-column">
                        <li class="nav-item">
                            <a href="#" class="nav-link mininav-toggle collapsed" @click="logout" aria-expanded="false">
                                <i class="demo-pli-unlock fs-5 me-2"></i>
                                <span class="nav-label ms-1">Se deconnecter</span>
                            </a>
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
    import SuperNavbar from '@/Components/Navbar/Super.vue';
    import TeacherNavbar from '@/Components/Navbar/Teacher.vue';
    import logo from '~/img/logo.svg';
    export default{
        name:'BackendLayoutVue',
        components:{
            SuperNavbar,
            TeacherNavbar,
        },
        computed:{
            isAuthenticated(){
                return this.$store.state.authenticated;
            },
            username(){
                return this.$store.state.user.name;
            },
            roleId(){
                return this.$store.state.user.role_id;
            }
        },
        data: function(){
            return {
                authenticated:false,
                logo:logo,
                sb:false,
                search:false,
                gridApi:null,
                date:new Date().getFullYear()
            }
        },
        mounted(){
            this.emitter.on('ready',(evt)=>{
                this.gridApi = evt.gridApi;
                this.search = true;
            })

            this.emitter.on('sidebar',()=>{
                this.sb = true;
            })

            this.iniJs();
        },
        methods:{
            onFilterTextBoxChanged() {
                this.gridApi.setGridOption(
                    "quickFilterText",
                    document.getElementById("filter-text-box").value,
                );
            },
            _logout(){
                this.$store.commit('SET_AUTHENTICATED',false);
                this.$store.dispatch('logout');
                this.$emit('logout');
            },


            toggle(){
                this.$store.state.navbar_min;
                this.max=!this.max;
                this.$store.commit('SET_NAVBAR_MIN',!this.max);
            },

            showSb(){
                document.getElementById('root').classList.add('sb--show')
            },

            hideSb(){
                document.getElementById('root').classList.remove('sb--show')
            },
            iniJs(){
                //./assets/vendors/popperjs/popper.min.js
                const myPopper = document.createElement("script");
                myPopper.setAttribute(
                "src",
                "/assets/vendors/popperjs/popper.min.js"
                );
                document.body.appendChild(myPopper);

                const myScript = document.createElement("script");
                myScript.setAttribute(
                "src",
                "/assets/vendors/bootstrap/bootstrap.min.js"
                );
                document.body.appendChild(myScript);

                const myNifty = document.createElement("script");
                myNifty.setAttribute(
                "src",
                "/assets/js/nifty.js"
                );
              //  document.body.appendChild(myNifty);
            },
        },
        watch:{
            $route (to, from){
                this.search = false;
                this.sb = false;
            }
        }
    }
</script>

<style scoped>
</style>
