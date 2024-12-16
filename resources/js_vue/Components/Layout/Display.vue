<template>
    <div ref="display">
        <div class="content__header content__boxed rounded-0">
            <div class="content__wrap">

                <div class="d-flex justify-content-between">
                    <slot name="breadcrumb"></slot>

                    <div>
                        <div class="btn-group" role="group">
                            <button id="btn-actions" type="button" class="btn btn-primary btn-xs dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                               <i class="pli-gears"></i> Actions
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btn-actions">
                               <slot name="actions"></slot>
                            </ul>
                        </div>
                    </div>
                </div>

                <slot name="page-header"></slot>

            </div>

        </div>
        <div class="content__boxed">
            <div class="content__wrap">
                <slot name="content"></slot>
            </div>
                    <!-- SIDEBAR -->
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <aside class="sidebar">
            <div class="sidebar__inner scrollable-content">
                <div class="sidebar__wrap">
                    <slot name="sidebar"></slot>
                </div>
            </div>
        </aside>
        <!-- ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ -->
        <!-- END - SIDEBAR -->
        </div>
    </div>
</template>
<script>
    import img from '~/assets/img/logo.svg';
    export default{
        name:'DisplayLayout',
        components:{
        },
        props:{
            sb:{
                type:Boolean,
                default:false,
            },
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
            }
        },
        mounted(){
            if(this.sb){
                this.$emit('sb')
                console.log("emitted!")
            }else{
                this.$emit('hsb');
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
            },

        },

        watch:{
        },
    }
</script>
