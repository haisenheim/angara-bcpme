<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES MATIERES',path:'/matieres'}" :link_2="'LISTE DES MATIERES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES MATIERES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h2>GESTION DES MATIERES</h2>
                        <router-link class="btn btn-primary" to="/matieres/create"><i class="demo-pli-add me-1 fs-5"></i> NOUVELLE MATIERE</router-link>
                    </div>
                    <div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>MATIERE</th>
                                    <th>CODE</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="matiere in matieres" :key="matiere.id">
                                    <td>{{ matiere.name }}</td>
                                    <td>{{ matiere.code }}</td>
                                    <td>-</td>
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
    name:"MatiereIndex",
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
            description:'Liste des matieres de l\'ecole',
            matieres:[],
        }
    },
    methods:{
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/matieres')
            .then((res)=>{
                console.log(res.data);
                this.matieres = res.data;
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
