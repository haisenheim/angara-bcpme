<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES ENSEIGNANTS',path:'/enseignants'}" :link_2="'NOUVEL ENSEIGNANT'"></BreadCrumb>
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
                                <label for="">PHOTO DE L'ENSEIGNANT</label>
                                <input type="file"  @change="handlePhoto($event)" class="form-control" >
                            </div>
                        </div>
                        <div class="d-flex gap-4 flex-wrap justify-content-center">
                            <div class="form-group">
                                <label for="">NOM</label>
                                <input type="text" v-model="enseignant.last_name" placeholder="NOM de l'enseignant" class="form-control i-name">
                            </div>
                            <div class="form-group">
                                <label for="">PRENOM</label>
                                <input type="text" v-model="enseignant.first_name" placeholder="NOM de l'enseignant" class="form-control i-name">
                            </div>
                            <div class="form-group">
                                <label for="">DATE DE NAISSANCE</label>
                                <input type="date" v-model="enseignant.dtn"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">ADRESSE</label>
                                <input type="text" v-model="enseignant.address"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">TELEPHONE</label>
                                <input type="text" v-model="enseignant.phone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">EMAIL</label>
                                <input type="email" v-model="enseignant.email" class="form-control">
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
    name: "CreateEnseignant",
    data() {
      return {
        title:'Nouvelle Enseignant',
        description:'Formulaire de creation d\'une nouvelle enseignant',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        enseignant:{},
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
            if(this.enseignant.first_name!=undefined || this.enseignant.last_name!=undefined){
                return `${this.enseignant.last_name!=undefined?this.enseignant.last_name:''}  ${this.enseignant.first_name!=undefined?this.enseignant.first_name:''}`
            }else{
                return "Nouvel Enseignant";
            }
        }
    },
    methods: {
      selectPays(event){
        let id = event.target.value;
        this.enseignant.pay_id = id;
      },
      selectDiplome(event){
        let id = event.target.value;
        this.enseignant.diplome_id = id;
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
            this.enseignant.tuteur_id = this.tuteur.id;

            if(this.photo !=null){
                form.append("photo",this.photo);
                //this.enseignant.photo = this.photo;
                console.log(this.photo)
            }
            form.append('enseignant',JSON.stringify(this.enseignant))
            await this.api.post('/api/enseignants',form,{
                        headers:{
                            'Content-Type':'multipart/form-data'
                        }
                    })
                        .then((res)=>{
                            this.enseignant = res.data;
                            this.toaster.success("Creation de l'enseignant effectuee avec succes !!!");
                            this.$router.back();
                           // this.$router.push({name:'super_enseignant',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'enseignant!");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
            await this.api.get('/api/enseignants/create')
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




