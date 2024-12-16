<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES INSCRIPTIONS',path:'/inscriptions'}" :link_2="'LISTE DES INSCRIPTIONS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES INSCRIPTIONS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2>LISTE DES INSCRIPTIONS</h2>
                        <router-link class="btn btn-primary" to="/inscriptions/create">NOUVELLE INSCRIPTION</router-link>
                    </div>
                    <div class="mt-4">
                        <div class="mb-2">
                            <input @input="search" type="text" style="max-width: 400px;" class="form-control bg-light p-1 border-white" placeholder="Rechercher ...">
                        </div>
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <th>DATE</th>
                                <th>MATRICULE</th>
                                <th>ETUDIANT</th>
                                <th>FILIERE</th>
                                <th>NIVEAU</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="item in filtered" :key="item.id">
                                    <td>{{ item.date }}</td>
                                    <td>{{ item.etudiant.matricule }}</td>
                                    <td>{{ item.etudiant.name }}</td>
                                    <td>{{ item.filiere?item.filiere.code:item.filiere_id }}</td>
                                    <td>NIVEAU {{ item.niveau_id }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
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
    name:"InscriptionIndex",
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
            description:'Historique des inscriptions de l\'annee en cours',
            inscriptions:[],
            filtered:[],
        }
    },
    methods:{
        search(event){
            console.log(event.target.value);
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.inscriptions];
                    } else {
                        this.filtered = this.inscriptions.filter((item) => {
                            return item.etudiant.name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.etudiant.matricule.toLowerCase().startsWith(event.target.value.toLowerCase()) || item.date.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.filiere.name.toLowerCase().startsWith(event.target.value.toLowerCase());
                        });
                    }
                }, 250);
            }
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/inscriptions')
            .then((res)=>{
                console.log(res.data);
                this.inscriptions = res.data;
                this.filtered = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
                loader.hide();
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
