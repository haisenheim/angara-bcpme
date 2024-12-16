<template>
        <div id="root_" class="root front-container">
        <section id="content" class="content">
            <div class="content__boxed w-100 min-vh-100 d-flex flex-column align-items-center justify-content-center">
                <div :id="id" class="content__wrap">
                    <router-view @log="justLogin"></router-view>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default{
        name:'DefaultLayoutVue',
        components:{
        },
        computed:{
            isAuthenticated(){
                return this.$store.state.authenticated;
            },
            id(){
                return this.$store.state.authenticated?'log':'nolog'
            }

        },
        data: function(){

        },
        methods:{
            justLogin(){
                this.$store.commit('SET_AUTHENTICATED',true);
                this.$emit('login');
            },
        }
    }
</script>

<style scoped>
    #nolog{
        padding: 0;
    }
</style>
