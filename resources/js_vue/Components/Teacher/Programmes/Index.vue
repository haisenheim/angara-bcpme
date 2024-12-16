<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES PROGRAMMES',path:'/programmes'}" :link_2="'LISTE DES PROGRAMMES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES PROGRAMMES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h2>LISTE DES PROGRAMMES</h2>
                        <router-link class="btn btn-primary" to="/programmes/create">NOUVEAU COURS</router-link>
                    </div>
                    <div class="mt-4">
                        <div class="mb-2">
                            <input @input="search" type="text" style="max-width: 400px;" class="form-control bg-light p-1 border-white" placeholder="Rechercher ...">
                        </div>
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <th>FILIERE</th>
                                <th>NIVEAU</th>
                                <th>SEMESTRE</th>
                                <th>MATIERE</th>
                                <th>COEFFICIENT</th>
                                <th>CREDIT</th>
                                <th></th>
                            </thead>
                            <tbody>
                                <tr v-for="item in filtered" :key="item.id">
                                    <td>{{ item.filiere.code }}</td>
                                    <td>{{ item.niveau_id }}</td>
                                    <td>{{ item.semestre }}</td>
                                    <td>{{ item.matiere.name }}</td>
                                    <td>{{ item.coef }}</td>
                                    <td>{{ item.credits }}</td>
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
export default {
    name:"ProgrammeIndex",
    ...mapActions({
        logout:"logOut"
    }),
    components:{
    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des programmes',
            programmes:[],
            filtered:[],
        }
    },
    methods:{
        search(event){
            console.log(event.target.value);
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.programmes];
                    } else {
                        this.filtered = this.programmes.filter((item) => {
                            return item.matiere.name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.matiere.code.toLowerCase().startsWith(event.target.value.toLowerCase()) || item.date.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.filiere.name.toLowerCase().startsWith(event.target.value.toLowerCase());
                        });
                    }
                }, 250);
            }
        },
      async load(){
            await this.api.get('/api/cours')
            .then((res)=>{
                console.log(res.data);
                this.programmes = res.data;
                this.filtered = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
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
