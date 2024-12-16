<template>
    <!-- Navigation menu -->
    <div class="mainnav__top-content scrollable-content pb-5">

        <!-- Profile Widget -->
        <div class="mainnav__profile mt-3 d-flex3">

            <div class="mt-2 d-mn-max"></div>

            <!-- Profile picture  -->
            <div class="mininav-toggle text-center py-2">
                <img class="mainnav__avatar img-md rounded-circle border" src="" alt="Profile Picture">
            </div>

            <div class="mininav-content collapse d-mn-max">
                <div class="d-grid">

                    <!-- User name and position -->
                    <button class="d-block btn shadow-none p-2" data-bs-toggle="collapse" data-bs-target="#usernav" aria-expanded="false" aria-controls="usernav">
                        <span class="dropdown-toggle d-flex justify-content-center align-items-center">
                            <h6 class="mb-0 me-3">{{ username }}</h6>
                        </span>
                        <small class="text-muted">Administrator</small>
                    </button>

                    <!-- Collapsed user menu -->
                    <div id="usernav" class="nav flex-column collapse">
                        <a href="#" class="nav-link d-flex justify-content-between align-items-center">
                            <span><i class="demo-pli-mail fs-5 me-2"></i><span class="ms-1">Messages</span></span>
                            <span class="badge bg-danger rounded-pill">14</span>
                        </a>
                        <a href="#" class="nav-link">
                            <i class="demo-pli-male fs-5 me-2"></i>
                            <span class="ms-1">Profil</span>
                        </a>
                        <a href="#" class="nav-link">
                            <i class="demo-pli-gear fs-5 me-2"></i>
                            <span class="ms-1">Parametres de l'ecole</span>
                        </a>
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
    import SingleMenuItem from '../SingleMenuItem.vue';
    import HasSubMenu from '../HasSubMenu.vue';
    export default{
        name:'AdminNavbar',
        components:{
            SingleMenuItem,
            HasSubMenu,
        },
        data: function(){
            return {
                authenticated:this.$store.state.authenticated,

                menu:[
                    {
                        title:'ACCUEIL',
                        icon:'demo-pli-home',
                        sequence:1,
                        path:'/admin/home'
                    },
                    {
                        title:'ELEVES',
                        icon:'demo-pli-find-user',
                        sequence:2,
                        path:'/super/eleves'
                    },
                    {
                        title:'Comptes',
                        icon:'demo-pli-users',
                        sequence:3,
                        links:[
                            {
                                label:'Utilisateurs',
                                path:'/admin/users'
                            },
                            {
                                label:'Roles',
                                path:'/admin/roles'
                            },
                        ]
                    },
                    {
                        title:'PARAMETRES',
                        icon:'demo-pli-gears',
                        sequence:4,
                        links:[
                            {
                                label:'Salles de classe',
                                path:'/admin/salles'
                            },
                            {
                                label:'Matieres',
                                path:'/admin/matieres'
                            },
                            {
                                label:'Classes',
                                path:'/admin/classes',
                            },
                            {
                                label:'Sites',
                                path:'/admin/sites',
                            },
                            {
                                label:'Classes',
                                path:'/admin/classes',
                            }
                        ]
                    },


                ],
            }
        },
        computed:{
            username(){
                return this.$store.state.user.name;
            }
        },

        methods:{
            ...mapActions({
                signOut:"auth/logOut"
            }),
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

</style>
