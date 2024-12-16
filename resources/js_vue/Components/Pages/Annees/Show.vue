<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TUTEURS',path:'/tuteurs'}" :link_2="tuteur.name"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="tuteur.name" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h2>{{ tuteur.name }}</h2>
                            <div class="mb-1">
                                <i class="pli-home"></i> <span class="text-bold">{{ tuteur.address }}</span>
                            </div>
                            <div class="mb-1">
                                <i class="pli-phone"></i> <strong class="text-bold">{{ tuteur.phone }}</strong>
                            </div>
                            <div class="mb-1">
                                <i class="pli-email"></i> <strong class="text-bold">{{ tuteur.email }}</strong>
                            </div>
                        </div>
                        <div style="min-width: 600px;" class="bg-light p-3 rounded-2">
                        <h5>LISTE DES ETUDIANTS</h5>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ETUDIANT</th>
                                    <th>MATRICULE</th>
                                    <th>INFOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in etudiants" :key="item.id">
                                    <td>
                                        <div><img :src="item.photo" class="img-circle img-rounded rounded-circle" alt="" style="width: 70px;"></div>
                                    </td>
                                    <td><router-link :to="{name:'etudiants_show',params:{tkn:item.token}}" >{{ item.name }}</router-link></td>
                                    <td>{{ item.matricule }}</td>
                                    <td>{{ item.classe }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </template>
    </Display>

</template>

<script>
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
export default {
    name:"TuteurShow",
    props:{
        tkn:String,
    },
    components:{
        Display,
        PageHeader,
        BreadCrumb,

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Fiche Tuteur',
            tuteur:{},
            etudiants:[],
        }
    },
    methods:{
      async load(){
            await this.api.get(`/api/tuteurs/${this.tkn}`)
            .then((res)=>{
                console.log(res.data);
                this.tuteur = res.data.tuteur;
                this.etudiants = res.data.etudiants;
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
