<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES ECOLES',path:'/super/ecoles'}" :link_2="'NOUVELLE ECOLE'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="title" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container d-flex justify-content-center">
            <form enctype="multipart/form-data" @submit.prevent="submit()">
                <div class="card" style="width: 600px;">
                    <div class="card-body">
                    <div class="">
                        <div class="form-group">
                        <label for="">NOM</label>
                        <input type="text" v-model="ecole.name" required placeholder="Nom de l'ecole" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ADRESSE</label>
                            <input type="text" v-model="ecole.address" placeholder="Adresse de localisation" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">TELEPHONE</label>
                            <input type="text" v-model="ecole.phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">EMAIL</label>
                            <input type="email" v-model="ecole.email" class="form-control">
                        </div>
                        <div class="form-group">
                        <label for="">PHOTO</label>
                        <input type="file" @change="handlePhoto($event)" class="form-control">
                        </div>
                        <div class="form-group">
                        <label for="">LOGO</label>
                        <input type="file" @change="handleLogo($event)" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">GROUPE SCOLAIRE</label>
                            <select name="" class="form-control" @change="selectGroupe($event)" id="">
                                <option value="">SELECTIONNER UN GROUPE</option>
                                <option v-for="opt in groupes" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">PAYS</label>
                            <select name="" class="form-control" @change="selectPays($event)" id="">
                                <option value="">SELECTIONNER UN PAYS</option>
                                <option v-for="opt in pays" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">VILLE</label>
                            <select name="" class="form-control" @change="selectVille($event)" id="">
                                <option value="">SELECTIONNER UNE VILLE</option>
                                <option v-for="opt in villes" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
                        </div>
                        <fieldset>
                            <legend>COMPTE ADMINISTRATEUR</legend>
                            <div class="form-group">
                                <label for="">NOM</label>
                                <input type="text" v-model="user.name" required placeholder="Nom de l'administrateur" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">TELEPHONE</label>
                                <input type="text" v-model="user.phone" required placeholder="Telephone de l'administrateur" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">EMAIL</label>
                                <input type="email" v-model="user.email" required placeholder="Adresse e-mail de connexion au compte" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">MOT DE PASSE</label>
                                <input type="password" v-model="user.password" required placeholder="Mot de passe de connexion au compte" class="form-control">
                            </div>
                        </fieldset>
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

  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
    },
    name: "CreateEcole",
    data() {
      return {
        title:'Nouvelle Ecole',
        description:'Formulaire de creation d\'une nouvelle ecole',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        ecole:{},
        groupes:[],
        pays:[],
        villes:[],
        photo:null,
        logo:null,
        user:{},
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      selectGroupe(event){
        this.ecole.groupe_id = event.target.value;
      },
      handleLogo(event){
        this.logo = event.target.files[0];
        console.logo(this.logo)
      },
      handlePhoto(event){
        this.photo = event.target.files[0];
        console.logo(this.photo)
      },
      selectPays(event){
        let id = event.target.value;
        this.ecole.pay_id = id;
        let loader = this.$loading.show();
        this.api.get(`/api/country/cities/${id}`)
        .then((res)=>{
            this.villes = res.data;
        }).catch((err)=>{
            console.log(err);
            this.toaster.err("Une erreur est survenue lors du chargement des villes!");
        }).finally(()=>loader.hide())
      },
      selectVille(event){
        this.ecole.ville_id = event.target.value;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('ecole',JSON.stringify(this.ecole))
            form.append('user',JSON.stringify(this.user));
            if(this.photo!=null){
                form.append('photo',this.photo);
            }
            if(this.logo!=null){
                form.append('logo',this.logo);
            }
            await this.api.post('/api/super/ecoles',form)
                        .then((res)=>{
                            this.ecole = res.data;
                            this.toaster.success("Creation de l'ecole effectuee avec succes !!!");
                            this.$router.push({path:'/super/ecoles'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'ecole!");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
            await this.api.get('/api/super/ecoles/create')
                        .then((res)=>{
                            this.groupes=res.data.groupes;
                            this.pays = res.data.pays;
                        })
                        .catch((err)=>console.error(err));
        }
    },
  };
  </script>

<style scoped>

    .bg{
      background-color: #efefef;
    }
    .steppy-item-counter {
        height: 40px !important;
        width: 40px !important;
        border: none !important;
      }

      .steppy-item-counter .number {
        font-size: 1rem !important;
      }

      .controls {
        display: flex !important;
        flex-direction: row !important;
        gap: 2rem !important;
      }

      .controls .btn {
        align-self:normal !important;
        background-color: #3d7;
      }

      input[type='radio']:checked:after {
        background: #3d9970 !important;
      }

      .v-container div.controls .btn--default-2 {
        margin: auto !important;
        text-align: center !important;
        background: #3d9970 !important;
        border-radius: 4px !important;
        border: none !important;
        height: 40px !important;
        width: 40% !important;
        font-size: 1rem !important;
        color: #000407 !important;
        font-weight: bold !important;
      }

      .btn[data-v-bd8cdabb] {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px 16px;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        line-height: 1.5;
        transition: all .15s;
        border-radius: 4px;
        width: fit-content;
        font-size: .75rem;
        color: #fff;
        background-color: #3d9970;
        border: 1px solid #f0f0f0;
      }
</style>
