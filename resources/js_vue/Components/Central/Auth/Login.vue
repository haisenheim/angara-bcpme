<template>
<div style="padding: 0;" class="d-flex flex-wrap justify-content-between">
    <div style="width: 1500px">
        <img :src="bg" style="width: 100%; height: 100vh" alt="">
    </div>
    <div id="login" class="">
        <div style="display: table-cell; vertical-align: middle; " class="card shadow-lg">
            <div class="card-body">
                <div class="text-center">
                    <div>
                        <img :src="logo" class="rounded-circle" style="width: 100px; height: 100px; margin-bottom: 20px;" alt="">
                    </div>
                    <h1 class="h3">Connexion</h1>
                    <p>Connectez vous a votre compte</p>
                </div>
                <form @submit.prevent="login">

                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" v-model="user.email" placeholder="Email" autofocus>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" v-model="user.password" class="form-control" placeholder="Password">
                    </div>
                    <div class="d-grid mt-5">
                        <button class="btn btn-primary btn-lg" type="submit">Se connecter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>
<script>
    import axios from 'axios';
    import bg from '~/img/cover.jpg';
    import logo from '~/img/logo.svg';
    export default{
        name:'LoginVue',
        components:{
        },
        data:function(){
            return {
                user:{
                    email:'',
                    password:'',
                },
                logo:logo,
                cities:null,
                filteredCities:null,
                city:null,
                bg:bg,
            }
        },
        beforeCreate(){
            //this.checkData();
            let token = this.$store.getters.getToken;
            let auth = this.$store.getters.getUser;
            console.log(token);
            console.log(auth);

        },
        mounted(){

        },
        methods:{
            checkData(){
                let token = this.$store.getters.getToken;
                let auth = this.$store.getters.getUser;
                console.log(token);
                console.log(auth);
            },
            search(event){
                {
                    setTimeout(() => {
                        if (!event.query.trim().length) {
                            this.filteredCities = [...this.cities];
                        } else {
                            this.filteredCities = this.cities.filter((country) => {
                                return country.name.toLowerCase().startsWith(event.query.toLowerCase());
                            });
                        }
                    }, 250);
                }
            },
            async login(){
                 axios.post('/api/auth/login',this.user)
                .then((res)=>{
                    //console.log(res.data)
                    this.$store.commit('SET_AUTHENTICATION',res.data);
                   // this.checkData();
                    this.$emit('log');
                    let role_id = res.data.user.role_id;
                    console.log(role_id);
                    if(role_id == 1){
                        this.$router.push({path:'/dashboard'});
                    }

                    if(role_id == 2){
                        this.$router.push({path:'/prof/dashboard'});
                    }

                })
                .catch((err)=>{
                    console.log(err);
                })

            }
        }
    }
</script>
<style scoped>
 .content__wrap{
    width:100%;
    padding: 0;
 }
 #login{
    position: absolute;
    right: 1px;
 }
 #login .card{
    height: 100vh;
    border-radius: 0;
    min-width: 400px;
 }
</style>












