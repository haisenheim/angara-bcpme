<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'Accueil',path:'/dashboard'}" :link_2="'Tableau de bord'"></BreadCrumb>
        </template>

        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <h2>TABLEAU DE BORD</h2>

                </div>

            </div>
        </template>
    </Display>

</template>

<script>
import {mapActions} from 'vuex'
import axios from "axios";
import Display from '../../Layout/Display.vue';
import PageHeader from '../../Layout/Includes/PageHeader.vue';
import BreadCrumb from '../../Layout/Includes/Breadcrumb.vue';
import AutoComplete from 'primevue/autocomplete';
export default {
    name:"SuperDashboard",
    ...mapActions({
        logout:"logOut"
    }),
    components:{
        Display,
        PageHeader,
        BreadCrumb,
        AutoComplete,

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Bienvenu dans SKULU, la plateforme de gestion scolaire la plus revolutionnaire jamais concu!',
            //entreprise:this.$store.state.auth.entreprise,
            cities:null,
            filteredCities:null,
            city:null,
        }
    },
    methods:{
        logout(){
            this.$store.dispatch('logOut')
            .then(()=>{
                console.log("Deconnexion");
            })
            .catch((err)=>{
                console.log("Echec de deconnexion!");
                console.log(err);
            })
            .finally(()=>{
                this.$router.push({path:'/login'});
            })

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
    },
    mounted(){
        axios.get('/api/country/cities/1')
            .then((res)=>{
                console.log(res.data);
                this.cities = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
    }

}
</script>

<style scoped>

</style>
