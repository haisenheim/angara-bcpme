<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DE LA BIBLIOTHEQUE',path:'/bibliotheque'}" :link_2="'NOUVEAU DOCUMENT'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="title" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container d-flex justify-content-center">
            <form @submit.prevent="submit()">
                <div class="card" style="width: 600px;">
                    <div class="card-body">
                    <div class="">
                        <div class="form-group">
                            <label for="">TITRE</label>
                            <input type="text" v-model="livre.name" required placeholder="Titre du livre" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">AUTEUR</label>
                            <input type="text" v-model="livre.auteur" required placeholder="Auteur" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">DESCRIPTION</label>
                            <textarea name="" v-model="livre.description" class="form-control" placeholder="Breve description du contenu" id="" cols="30" rows="5"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">DATE DE PUBLICATION</label>
                            <input type="number" v-model="livre.annee" required placeholder="Annee de publication" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">DOMAINE DE COMPETENCE</label>
                            <select required @change="selectDomaine" id="" class="form-control">
                                <option value="">Selectionner un domaine ...</option>
                                <option v-for="opt in domaines" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="">FICHIER PDF</label>
                            <input type="file" @change="handleFile" required  class="form-control">
                        </div>
                    </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit"  class="btn btn-primary"><i class="pli-data-yes fs-4 me-1"></i> ENREGISTRER</button>
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
    name: "CreateLivre",
    data() {
      return {
        title:'Nouveau Livre',
        description:'Formulaire de creation d\'un nouveau livre',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        livre:{},
        file:null,
        domaines:[],
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      selectDomaine(event){
        this.livre.domaine_id = event.target.value;
      },
      handleFile(event){
        this.file = event.target.files[0];
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('livre',JSON.stringify(this.livre))
            form.append('file',this.file);
            await this.api.post('/api/livres',form,this.multipart)
                        .then((res)=>{
                            this.livre = res.data;
                            this.toaster.success("Creation de la livre effectuee avec succes !!!");
                            this.$router.back();
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la livre");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
        await this.api.get('/api/livres/create')
                        .then((res)=>{
                            this.domaines = res.data.domaines;
                        })
                        .catch((err)=>console.error(err));
        }
    },
  };
  </script>

<style scoped>

  .form-group{
    margin-top: 20px;
  }
</style>
