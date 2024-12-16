<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES ETUDIANTS',path:'/etudiants'}" :link_2="'NOUVEL ETUDIANT'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="name" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container">
            <form @submit.prevent="submit" method="post">
                <div class="card">
                    <div class="card-body">
                    <div class="">
                        <div class="form-group mb-3 d-flex gap-2">
                            <div class="" style="width: 100px;">
                                <img class="img-thumbnail img-avatar img-circle" :src="path"  alt="">
                            </div>
                            <div class="align-content-center" style="min-width: 100px;">
                                <label for="">PHOTO DE L'ETUDIANT</label>
                                <input type="file"  @change="handlePhoto($event)" class="form-control" >
                            </div>
                            <div class="align-content-center mt-3">
                                <AutoComplete placeholder="Nom du tuteur" pt:input:class="form-control" pt:input:style="min-width:300px; height:fit-content" v-model="tuteur" optionLabel="name" :suggestions="filteredTuteurs" @complete="search">
                                    <template #option="slotProps">
                                        <div class="fs-6">
                                            <div>{{ slotProps.option.name }} - {{ slotProps.option.phone }}</div>
                                        </div>
                                    </template>
                                </AutoComplete>
                            </div>
                            <div class="mt-3 align-content-center">
                                <router-link class="btn btn-primary" to="/tuteurs/create"><i class="pli-add fs-5 me-1"></i> Creer le tuteur</router-link>
                            </div>
                        </div>
                        <div class="d-flex gap-4 flex-wrap justify-content-center">
                            <div class="form-group">
                                <label for="">NOM</label>
                                <input type="text" v-model="etudiant.last_name" placeholder="NOM de l'etudiant" class="form-control i-name">
                            </div>
                            <div class="form-group">
                                <label for="">PRENOM</label>
                                <input type="text" v-model="etudiant.first_name" placeholder="NOM de l'etudiant" class="form-control i-name">
                            </div>
                            <div class="form-group">
                                <label for="">DATE DE NAISSANCE</label>
                                <input type="date" v-model="etudiant.dtn"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">LIEU DE NAISSANCE</label>
                                <input type="text" v-model="etudiant.lieu"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NOM DU PERE</label>
                                <input type="text" v-model="etudiant.pere"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NOM DE LA MERE</label>
                                <input type="text" v-model="etudiant.mere"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">ADRESSE</label>
                                <input type="text" v-model="etudiant.address"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">TELEPHONE</label>
                                <input type="text" v-model="etudiant.phone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">EMAIL</label>
                                <input type="email" v-model="etudiant.email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NATIONALITE</label>
                                <select name="" class="form-control" @change="selectPays($event)" id="">
                                    <option value="">SELECTIONNER UN PAYS</option>
                                    <option v-for="opt in pays" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">DERNIER DIPLOME OBTENU</label>
                                <select name="" class="form-control" @change="selectDiplome($event)" id="">
                                    <option value="">SELECTIONNER </option>
                                    <option v-for="opt in diplomes" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit"  class="btn btn-primary"><i class="bx bx-save"></i> ENREGISTRER</button>
                    </div>
                </div>
            </form>
        </div>
     </template>
    </Display>
</template>
<script>
  import { createToaster } from "@meforma/vue-toaster";
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import Display from '@/Components/Layout/Display.vue';
  import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
  import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
  import AutoComplete from 'primevue/autocomplete';
  import avatar from '~/img/avatar.png';
  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
      AutoComplete,
    },
    name: "CreateEtudiant",
    data() {
      return {
        title:'Nouvelle Etudiant',
        description:'Formulaire de creation d\'une nouvelle etudiant',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        etudiant:{},
        photo:null,
        pays:[],
        tuteurs:[],
        tuteur:{},
        filteredTuteurs:[],
        path:avatar,
      };
    },

    mounted(){
      this.load();
    },
    computed:{
        name(){
            if(this.etudiant.first_name!=undefined || this.etudiant.last_name!=undefined){
                return `${this.etudiant.last_name!=undefined?this.etudiant.last_name:''}  ${this.etudiant.first_name!=undefined?this.etudiant.first_name:''}`
            }else{
                return "Nouvel Etudiant";
            }
        }
    },
    methods: {
      selectPays(event){
        let id = event.target.value;
        this.etudiant.pay_id = id;
      },
      selectDiplome(event){
        let id = event.target.value;
        this.etudiant.diplome_id = id;
      },
      handlePhoto(event){
        this.photo = event.target.files[0];
        this.path = URL.createObjectURL(this.photo)
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            this.etudiant.tuteur_id = this.tuteur.id;

            if(this.photo !=null){
                form.append("photo",this.photo);
                //this.etudiant.photo = this.photo;
                console.log(this.photo)
            }
            form.append('etudiant',JSON.stringify(this.etudiant))
            await this.api.post('/api/etudiants',form,{
                        headers:{
                            'Content-Type':'multipart/form-data'
                        }
                    })
                        .then((res)=>{
                            this.etudiant = res.data;
                            this.toaster.success("Creation de l'etudiant effectuee avec succes !!!");
                            this.$router.back();
                           // this.$router.push({name:'super_etudiant',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'etudiant!");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
            await this.api.get('/api/etudiants/create')
                        .then((res)=>{
                            this.pays = res.data.pays;
                            this.tuteurs = res.data.tuteurs;
                            this.diplomes = res.data.diplomes;
                        })
                        .catch((err)=>console.error(err));
        },
        search(event){
            {
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredTuteurs = [...this.tuteurs];
                    } else {
                        this.filteredTuteurs = this.tuteurs.filter((tuteur) => {
                            return tuteur.name.toLowerCase().startsWith(event.query.toLowerCase());
                        });
                    }
                }, 250);
            }
        },
    },
  };
  </script>

<style scoped>
    .my-auto>input{
        min-width: 400px;
    }

    .form-control{
        min-width: 200px;
    }

    .img-avatar{
        width:100px;
        height: 100px;
        object-fit: cover;
    }

    .img-circle{
        border-radius: 50%;
    }

    input.i-name{
        min-width: 360px;
    }
    ul{
        margin-bottom: 0;
        padding-left: 0;
    }

</style>




