<template>
    <!-- Navigation menu -->
    <div class="mainnav__top-content scrollable-content pb-5">

        <!-- Profile Widget -->
        <div class="mainnav__profile mt-3 d-flex3">

            <div class="mt-2 d-mn-max"></div>

            <!-- Profile picture  -->
            <div class="mininav-toggle text-center py-2">
                <img class="mainnav__avatar img-md rounded-circle border" :src="avatar" alt="Profile Picture">
            </div>

            <div class="mininav-content collapse d-mn-max">
                <div class="d-grid">

                    <!-- User name and position -->
                    <button class="d-block btn shadow-none p-2" data-bs-toggle="collapse" data-bs-target="#usernav" aria-expanded="false" aria-controls="usernav">
                        <span class="dropdown-toggle d-flex justify-content-center align-items-center">
                            <h6 class="mb-0 me-3">{{ user.name }}</h6>
                        </span>
                        <small class="text-muted">Super Admin</small>
                    </button>

                    <!-- Collapsed user menu -->
                    <div id="usernav" class="nav flex-column collapse">

                        <router-link to="/profile" class="nav-link">
                            <i class="demo-pli-male fs-5 me-2"></i>
                            <span class="ms-1">Profile</span>
                        </router-link>
                        <a href="javascript:void(0)" @click="logout()" class="nav-link">
                            <i class="demo-pli-unlock fs-5 me-2"></i>
                            <span class="ms-1">Se deconnecter</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
        <!-- End - Profile widget -->

        <!-- Navigation Category -->
        <div class="mainnav__categoriy py-3">
            <ul class="mainnav__menu nav flex-column">
                <template v-for="(mem, index) in menu" :key="index">
                    <HasSubMenu :sequence="mem.sequence" :icon="mem.icon" :links="mem.links" v-if="mem.links!==undefined">{{ mem.title }}</HasSubMenu>
                    <SingleMenuItem v-else :sequence="mem.sequence" :icon="mem.icon" :path="mem.path" >{{ mem.title }}</SingleMenuItem>
                </template>
            </ul>
        </div>
        <!-- END : Navigation Category -->
</div>
<!-- End - Navigation menu -->
</template>
<script>
    import {mapActions} from 'vuex';
    import SubMenu from "./Submenu.vue";
    import SingleMenuItem from './SingleMenuItem.vue';
    import HasSubMenu from './HasSubMenu.vue';
    import avatar from "~/img/avatar.png";
    export default{
        name:'SuperAdminNavbar',
        components:{
            SubMenu,
            SingleMenuItem,
            HasSubMenu,
        },
        data: function(){
            return {
                authenticated:this.$store.state.authenticated,
                user:this.$store.state.user,
                avatar:avatar,
                menu:[
                {
                    title:'Tableau de bord',
                    icon:'pli-dashboard',
                    sequence:1,
                    path:'/dashboard'
                },
                {
                    title:'PUBLICATIONS',
                    icon:'pli-megaphone',
                    sequence:2,
                    path:'/posts'
                },
                {
                    title:'SCOLARITE',
                    icon:'pli-student-hat',
                    sequence:3,
                    links:[
                        {
                            label:'Incriptions',
                            path:'/inscriptions'
                        },
                        {
                            label:'Etudiants',
                            path:'/etudiants'
                        },
                        {
                            label:'Enseignants',
                            path:'/enseignants'
                        },
                        {
                            label:'Parents et Tuteurs',
                            path:'/tuteurs',
                        },
                    ]
                },
                {
                    title:'SUIVI',
                    icon:'pli-spell-check',
                    sequence:4,
                    links:[
                        {
                            label:'Emplois de temps',
                            path:'/emplois'
                        },
                        {
                            label:'Programmes academiques',
                            path:'/programmes'
                        },
                        {
                            label:'Sanctions',
                            path:'/sanctions'
                        },
                        {
                            label:'Appreciations',
                            path:'/appreciations'
                        },
                    ]
                },
                {
                    title:'EXAMENS',
                    icon:'pli-pen-2',
                    sequence:5,
                    links:[
                        {
                            label:'Evaluations',
                            path:'/examens'
                        },
                        {
                            label:'Notes',
                            path:'/notes'
                        }
                    ]
                },
                {
                    title:'Bibliotheque',
                    icon:'pli-books',
                    sequence:6,
                    path:'/bibliotheque'
                },
                {
                    title:'FINANCES',
                    icon:'pli-coins',
                    sequence:7,
                    links:[
                        {
                            label:'PAIEMENTS',
                            path:'/ecolages'
                        },
                        {
                            label:'AUTRES ENTREES',
                            path:'/entrees'
                        },
                        {
                            label:'AUTRES DEPENSES',
                            path:'/depenses'
                        }
                    ]
                },
                {
                    title:'RAPPORTS',
                    icon:'pli-files',
                    sequence:8,
                    links:[
                        {
                            label:'ETUDIANT',
                            path:'/reports/students'
                        },
                        {
                            label:'MENSUEL',
                            path:'/reports/month'
                        },
                        {
                            label:'ENSEIGNANT',
                            path:'/reports/teacher'
                        }
                    ]
                },

                    {
                        title:'ECOLE',
                        icon:'pli-university',
                        sequence:9,
                        links:[
                            {
                                label:'Salles de cours',
                                path:'/salles'
                            },
                            {
                                label:'Laboratoires',
                                path:'/laboratoires'
                            },
                            {
                                label:'Matieres',
                                path:'/matieres'
                            },
                            {
                                label:'Filieres',
                                path:'/filieres'
                            }
                        ]
                    },
                    {
                        title:'PARAMETRES',
                        icon:'pli-gears',
                        sequence:10,
                        links:[
                            {
                                label:'Tarifs',
                                path:'/tarifs'
                            },
                            {
                                label:'Types d\'evaluation',
                                path:'/evaluations'
                            },
                            {
                                label:'Criteres d\'evaluation',
                                path:'/criteres'
                            },
                            {
                                label:'Annees academiques',
                                path:'/annees'
                            },
                        ]
                    },
                ],
            }
        },
        computed:{
            username(){
                if(this.$store.state.user==!undefined){
                    console.log(this.$store.state.user);
                    return this.$store.state.user.name;
                }
                return ''

            }
        },

        mounted(){
            console.log(this.$store.state.user.name);
            this.load();
            //this.setMainNav();

        },

        methods:{
            ...mapActions({
                signOut:"auth/logOut"
            }),

            setMainNav(){
                const mainNav = document.getElementById( "mainnav-container" );

                let isMiniNav                   = null;
                let miniNavTogglers             = null;
                let miniNavContents             = null;
                let miniNavContentsCollapse     = null;

                //console.log(new bootstrap)

                // Update navigation variables if #mainnav-container is available.
                ( function () {
                    if (!mainNav) return;

                    isMiniNav                   = root.classList.contains( "mn--min" );
                    miniNavTogglers             = [...mainNav.querySelectorAll( ".mininav-toggle" )];
                    miniNavContents             = [...mainNav.querySelectorAll( ".mininav-content" )];

                    // Refresh the navigation when the transition ends.
                    mainNav.addEventListener( "transitionend", (e) => {
                        if (!e.target.classList.contains( "mainnav" ) || e.propertyName != "max-width" ) return;
                        buildNav();
                    });

                    // Initialize Bootstrap's Collapse
                    miniNavContentsCollapse   = miniNavContents.map(( collapseEl ) => {
                        const activeToggler = collapseEl.parentElement.querySelector( ".mininav-toggle.active" );
                        if ( activeToggler && ( !isMiniNav || window.innerWidth < 992 ) ) {
                            const parent = collapseEl.parentElement;
                            parent.classList.add( "open" );
                            let clp = new bootstrap.Collapse( collapseEl, { toggle: true } );
                            collapseEl.addEventListener( "transitionend", () => parent.classList.remove( "open" ), { once : true });
                            return clp;
                        }
                        return new bootstrap.Collapse( collapseEl, { toggle: false });
                    });

                })();

                // Default PopperJS options for the mini-navigation.
                const popperOptions = {
                    placement   : 'right-start',
                    strategy    : 'fixed',
                    modifiers   : [{
                        name: 'offset',
                        options: { offset: [ 0, 2 ] }
                    }]
                }

                // Update the navigation content and toggle selectors.
                const updateNav = () => {
                    miniNavTogglers = [...mainNav.querySelectorAll( ".mininav-toggle" )];
                    miniNavContents = [...mainNav.querySelectorAll( ".mininav-content" )];
                    buildNav();
                }

                // Cache component variables to the toggler.
                const addVariables = (el) => {
                    const miniNavContentTarget = el.parentElement.querySelector( ".mininav-content" );
                    //console.log(miniNavContentTarget)
                    el._mainnav = {
                        target   : miniNavContentTarget,
                        islink   : el.parentElement.classList.contains( "has-sub" ),
                        collapse : function () {
                            for ( let target of miniNavContentsCollapse ) {
                                if ( target._element === miniNavContentTarget ) {
                                    return target;
                                    break;
                                }
                            }
                        }()
                    };


                    el._mainnav.target.toggler = el;
                    el._mainnav.target.addEventListener( "show.bs.collapse", bsCollapseShow );
                    el._mainnav.target.addEventListener( "shown.bs.collapse", bsCollapseShow );
                }

                // Hides a collapsible element.
                const bsCollapseHide = (e) => {
                    if ( !e.target.classList.contains( "mininav-content" )) return;

                    if ( !isMiniNav || window.innerWidth < 992 ) e.target.toggler.classList.add( "collapsed" );
                    else e.target.removeEventListener( "hide.bs.collapse", bsCollapseHide);
                }

                // Shows a collapsible element.
                const bsCollapseShow = ( e ) => {
                    if ( !e.target.classList.contains( "mininav-content" )) return;

                    if ( !isMiniNav || window.innerWidth < 992 ) {
                        e.target.toggler.classList.remove( "collapsed" );
                    } else {
                        try {
                            e.target.popper.update();
                        } catch (err) {}
                    }
                }

                // Hide all the sub-menus.
                const hideAllMiniNavContent = (e) => {
                    if ( window.innerWidth >= 992 && ( !mainNav.contains( e.target ) || e.target.classList.contains( "mainnav__top-content" )) ) miniNavContentsCollapse.map( ( el ) => el.hide() );
                }

                // Toggle the submenus
                const toggleContent = (e) => {
                    if ( e.target._mainnav.target.classList.contains( "nav-label" ) && ( !isMiniNav || window.innerWidth < 992 ) ) return;


                    const _this = e.target._mainnav;
                    if( _this.islink ) e.preventDefault();


                    //  Hide all the sub-menus.
                    miniNavContentsCollapse.map( async (sm) => {
                        if ( !sm._element.contains( _this.target ) ) sm.hide();
                    });


                    // Show or Toggle the current sub-menu.
                    if ( e.type == "mouseenter" ) _this.collapse.show();
                    else _this.collapse.toggle();

                    [ "click", "touchend" ].forEach( evt => document.addEventListener( evt, hideAllMiniNavContent, { once: true } ));
                };

                // Build the navigation
                const buildNav = () => {
                    isMiniNav = root.classList.contains( "mn--min" );
                    let activeSub = null;


                    // Toggle the active submenu when navigation is in a max state.
                    if ( !isMiniNav ) activeSub = mainNav.querySelector(".has-sub > .mininav-toggle.nav-link.active + .mininav-content.nav");
                    if ( activeSub ) activeSub.classList.add("show");


                    miniNavTogglers.map( ( miniNavToggler ) => {

                        if ( !miniNavToggler._mainnav ) addVariables( miniNavToggler );
                        miniNavToggler.classList.add( "collapsed" );


                        if ( !isMiniNav || window.innerWidth < 992 ) {

                            miniNavToggler.addEventListener( "click", toggleContent );
                            [ "mouseenter", "touchend"].forEach( evt => miniNavToggler.removeEventListener( evt, toggleContent ));

                            if( miniNavToggler._mainnav.target.popper != null ) {
                                miniNavToggler._mainnav.target.popper.setOptions({
                                    scroll: false,
                                    resize: false
                                });
                                miniNavToggler._mainnav.target.popper.destroy();
                            }

                            miniNavToggler._mainnav.target.addEventListener( "hide.bs.collapse", bsCollapseHide );

                        } else {

                            // Hide all submenus
                            miniNavContentsCollapse.map( async sm => sm.hide() );


                            miniNavToggler._mainnav.target.popper = Popper.createPopper( miniNavToggler, miniNavToggler._mainnav.target, popperOptions);
                            miniNavToggler.removeEventListener( "click", toggleContent );
                            [ "mouseenter", "touchend"].forEach( evt => miniNavToggler.addEventListener( evt, toggleContent ));


                            // Hide all submenus when clicked outside of the main menu.
                            miniNavToggler._mainnav.target.removeEventListener( "hide.bs.collapse", bsCollapseHide );
                        }
                    });
                };

                //console.log(miniNavTogglers)

                // Initialize the main navigation
                //if ( miniNavTogglers ) buildNav();


                console.log('ici') //

            },

            load(){
                const body = document.body;
                const root = document.getElementById("root");
                // ----------------------------------------------
                document.querySelectorAll( ".nav-toggler" ).forEach( ( navToggler ) => {

                    navToggler.addEventListener( "click", () => {
                        if ( window.innerWidth < 992 || ( !root.classList.contains( "mn--min" ) && !root.classList.contains( "mn--max" ) )) {
                            root.classList.toggle( "mn--show" );
                        } else {
                            root.classList.toggle( "mn--min" );
                            root.classList.toggle( "mn--max" );
                        }
                    });
                });
                },

            async logout(){
                this.$store.dispatch('logOut')
                .then((res)=>{
                    console.log(res);
                    this.$emit('logout');
                    this.$router.push({path:'/login'});
                })
                .catch((err)=>{
                    console.log(err);
                })
            },
        }
    }

</script>
<style scoped>
  .collapsed.mininav-toggle.nav-link> .nav-label{
        font-weight: 900 !important;
    }
</style>
