

<template>
    <DefaultLayout v-if="!isAuthenticated"></DefaultLayout>
    <BackendLayout  v-if="isAuthenticated"></BackendLayout>
</template>
<script>
    import DefaultLayout from '@/Components/Layout/Default.vue';
    import BackendLayout from '@/Components/Layout/Backend.vue';

    export default{
        name:'AppVue',
        components:{
            DefaultLayout,
            BackendLayout
        },
        computed:{
            isAuthenticated(){
                return this.$store.state.authenticated;
            },
        },
        data: function(){
            return {
                //logo:img
            }
        },
        methods:{
            login(){

            },
            logout(){
                //this.$store.commit('SET_AUTHENTICATED',false);
            },
        }
    }
</script>
