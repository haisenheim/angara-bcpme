<template>
    <ul>
        <li><router-link to="/super/dashboard"> ACCUEIL</router-link></li>

    </ul>
</template>
<script>
    import {mapActions} from 'vuex';
    export default{
        name:'RevendeurNavbar',
        data: function(){
            return {
                authenticated:this.$store.state.authenticated,
            }
        },
        methods:{
            ...mapActions({
                signOut:"auth/logOut"
            }),
            async logout(){
                this.signOut()
                .then((res)=>{
                    console.log(res);
                    this.$emit('logout');
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
