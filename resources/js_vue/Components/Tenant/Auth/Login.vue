<template>
    <div>
        <h1 class="text-center">{{ name }}</h1>
        <div class="card shadow-lg">
            <div class="card-body">
                <div class="text-center">
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
</template>
<script>
    import axios from 'axios';
    import { mapActions } from 'vuex'
    export default{
        name:'LoginVue',
        data:function(){
            return {
                user:{
                    email:'',
                    password:'',
                }
            }
        },
        beforeCreate(){
            let token = this.$store.getters.getToken;
            let auth = this.$store.getters.getUser;
            console.log(token);
            console.log(auth);
            //this.getTenant();
        },
        methods:{
            checkData(){
                let token = this.$store.getters.getToken;
                let auth = this.$store.getters.getUser;
                console.log(token);
                console.log(auth);
            },
            async getTenant(){
                await this.api.get('/api/tenant')
                .then((res)=>{
                    this.$store.commit('SET_TENANT',res.data);
                })
            },
            async login(){
                 axios.post('/api/tenant/auth/login',this.user)
                .then((res)=>{
                    console.log(res.data)
                    this.$store.commit('SET_AUTHENTICATION',res.data);
                    this.checkData();
                    this.$emit('log');
                    this.$router.push({name:'admin_dashboard'});
                })
                .catch((err)=>{
                    console.log(err);
                })

            }
        },
        computed:{
            name(){
                if(this.$store.state.tenant!=null){
                    return this.$store.state.tenant.name;
                }else{
                    return '';
                }

            }
        },
        mounted(){
            this.getTenant();
        }
    }
</script>
