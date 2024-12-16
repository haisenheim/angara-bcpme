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
                        <small class="text-muted">Professeur</small>
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
                    path:'/prof/dashboard'
                },
                {
                    title:'HONORAIRES',
                    icon:'pli-coins',
                    sequence:2,
                    path:'/prof/honoraires'
                },

                {
                    title:'SUIVI',
                    icon:'pli-spell-check',
                    sequence:3,
                    links:[
                        {
                            label:'Emplois de temps',
                            path:'/prof/emplois'
                        },
                        {
                            label:'Programmes academiques',
                            path:'/prof/programmes'
                        },
                    ]
                },
                {
                    title:'EXAMENS',
                    icon:'pli-pen-2',
                    sequence:4,
                    links:[
                        {
                            label:'Evaluations',
                            path:'/prof/examens'
                        },
                        {
                            label:'Notes',
                            path:'/prof/notes'
                        }
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
           // console.log(this.$store.state.user.name);
            this.load();
            let popoverTriggerList = [].slice.call(document.querySelectorAll(
            '[data-bs-toggle="popover"]'))
            //console.log(popoverList);

        },

        methods:{
            ...mapActions({
                signOut:"auth/logOut"
            }),

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
