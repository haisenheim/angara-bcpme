<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES LIVRES',path:'/livres'}" :link_2="'LISTE DES LIVRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES LIVRES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="p-1 bg-light mb-2">
                <div class="">
                    <div class="d-flex justify-content-between">
                        <div></div>
                        <div>
                            <router-link class="btn btn-primary" to="/livres/create"><i class="pli-add"></i> Ajouter un livre</router-link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-3">
                <div v-for="livre in livres" :key="livre.id" class="card item">
                    <div class="card-body">
                        <router-link :to="{name:'livres_show', params: {tkn: JSON.stringify(livre)}}" >
                            <div class="text-center">
                                <img :src="livre.photo" class="img-thumbnail" alt="">
                                <div>
                                    <h6>{{ livre.name }}</h6>
                                </div>
                                <div>
                                    <span class="text-bold text-primary">{{ livre.auteur }}</span>
                                </div>
                                <div>
                                    <span class="badge bg-primary">{{ livre.domaine }}</span>
                                </div>
                                <div class="border-primary border-1">
                                    <span>{{livre.annee }}</span>
                                </div>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }">
                <span class="p-text-secondary block mb-5">Update your information.</span>
                <div class="flex align-items-center gap-3 mb-3">
                    <label for="username" class="font-semibold w-6rem">Username</label>
                    <InputText id="username" class="flex-auto" autocomplete="off" />
                </div>
                <div class="flex align-items-center gap-3 mb-5">
                    <label for="email" class="font-semibold w-6rem">Email</label>
                    <InputText id="email" class="flex-auto" autocomplete="off" />
                </div>
            </Dialog>
        </template>
    </Display>

</template>

<script>
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
import Dialog from 'primevue/dialog';
export default {
    name:"LivreIndex",
    components:{
        Display,
        PageHeader,
        BreadCrumb,
        Dialog,

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des livres de l\'ecole',
            livres:[],
            //livre:null,
            visible:false,
        }
    },
    methods:{
      toggle(event){
        let id = event.target.parent;
        console.log(id)
        this.visible = true;
      },  
      async load(){
            await this.api.get('/api/livres')
            .then((res)=>{
                console.log(res.data);
                this.livres = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
            })

        },

    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>
    .item{
        width: 180px;
    }

    .item a{
        text-decoration: none;
    }
    
    td{
        align-content: center;
        font-size: 11px;
    }
</style>







