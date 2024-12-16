<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TUTEURS',path:'/tuteurs'}" :link_2="'LISTE DES TUTEURS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES TUTEURS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h2>GESTION DES TUTEURS</h2>
                    </div>
                    <div>
                        <div class="mb-2">
                            <input @input="search" type="text" style="max-width: 400px;" class="form-control bg-light p-1 border-white" placeholder="Rechercher ...">
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>TUTEUR</th>
                                    <th>TELEPHONE</th>
                                    <th>EMAIL</th>
                                    <th>ADRESSE</th>
                                    <th>ETUDIANTS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tuteur in filtered" :key="tuteur.id">
                                    <td><router-link :to="{ name:'tuteurs_show',params:{tkn:tuteur.token}}">{{ tuteur.name }}</router-link></td>
                                    <td>{{ tuteur.phone }}</td>
                                    <td>{{ tuteur.email }}</td>
                                    <td>{{ tuteur.address }}</td>
                                    <td class=""><span class="badge bg-primary"> {{ tuteur.quantity }}</span></td>
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
    name:"TuteurIndex",
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
            description:'Liste des tuteurs de l\'ecole',
            tuteurs:[],
            filtered:[],
        }
    },
    methods:{
        search(event){
            console.log(event.target.value);
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.tuteurs];
                    } else {
                        this.filtered = this.tuteurs.filter((item) => {
                            return item.name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.email.toLowerCase().startsWith(event.target.value.toLowerCase()) || item.phone.toLowerCase().startsWith(event.target.value.toLowerCase())

                        });
                    }
                }, 250);
            }
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/tuteurs')
            .then((res)=>{
                console.log(res.data);
                this.tuteurs = res.data;
                this.filtered = res.data;
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
