<template>
    <Display>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES ECOLES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2>GESTION DES ECOLES</h2>
                        <router-link class="btn btn-primary" to="/super/ecoles/create">NOUVELLE ECOLE</router-link>
                    </div>
                    <div>
                        <div class="card" v-for="ecole in ecoles" :key="ecole.id">
                            <div class="card-body">
                                <div class="d-flex justify-center">
                                    <img :src="ecole.logo" class="img-circle" alt="">
                                    <div>
                                        <h5>{{ ecole.name }}</h5>
                                        <p><strong>{{ ecole.phone }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </Display>

</template>

<script>
import {mapActions} from 'vuex'
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
export default {
    name:"SuperEcolesIndex",
    ...mapActions({
        logout:"logOut"
    }),
    components:{
        Display,
        PageHeader,
        BreadCrumb,

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste de toutes les ecoles de la plateforme',
            ecoles:[],
        }
    },
    methods:{
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/super/ecoles')
            .then((res)=>{
                console.log(res.data);
                this.ecoles = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        }
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>

</style>
