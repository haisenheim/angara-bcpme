<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES PROGRAMMES',path:'/cours'}" :link_2="'NOUVEAU COURS'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="'NOUVEAU COURS AU PROGRAMME'" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container">
            <form enctype="multipart/form-data" @submit.prevent="submit()">
                <div class="card">
                    <div class="card-body">
                    <div class="">

                        <fieldset class="mt-4">
                            <legend></legend>
                            <div class="d-flex justify-content-center gap-5 flex-wrap">
                                <div class="form-group">
                                    <label for="">FILIERE</label>
                                    <select required class="form-control" @change="selectFiliere($event)" id="">
                                        <option value="">Choix de la filiere </option>
                                        <option v-for="opt in filieres" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">SEMESTRE</label>
                                    <select class="form-control" required @change="selectSemestre($event)" id="">
                                        <option value="">Choix du semestre </option>
                                        <option value="1">SEMESTRE 1</option>
                                        <option value="2">SEMESTRE 2</option>
                                        <option value="3">SEMESTRE 3</option>
                                        <option value="4">SEMESTRE 4</option>
                                        <option value="5">SEMESTRE 5</option>
                                        <option value="6">SEMESTRE 6</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">MATIERE</label>
                                    <select required class="form-control" @change="selectMatiere($event)" id="">
                                        <option value="">Choix de la matiere </option>
                                        <option v-for="opt in matieres" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">COEFFICIENT</label>
                                    <input type="number" v-model="cours.coefficient" placeholder="coefficient qu'on applique a la matiere" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">CREDITS</label>
                                    <input type="number" v-model="cours.credits" placeholder="nombre de credits" required class="form-control">
                                </div>
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
  import avatar from '~/img/avatar.png';
  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
    },
    name: "CreateInscription",
    data() {
      return {
        title:'Nouveau cours',
        description:'Formulaire de creation d\'un nouveau cours',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        cours:{},
        filieres:[],
        matieres:[],
        path:'',
      };
    },

    mounted(){
      this.load();
    },
    computed:{
    },
    methods: {
      selectFiliere(event){
        let id = event.target.value;
        this.cours.filiere_id = id;
      },
      selectMatiere(event){
        let id = event.target.value;
        this.cours.matiere_id = id;
      },
      selectSemestre(event){
        let id = event.target.value;
        this.cours.semestre = id;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('cours',JSON.stringify(this.cours))

            await this.api.post('/api/cours',form)
                        .then((res)=>{
                            this.cours = res.data;
                            this.toaster.success("Creation de l'cours effectuee avec succes !!!");
                            this.$router.push({path:'/programmes'})
                           // this.$router.push({name:'super_cours',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'cours!");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
            await this.api.get('/api/cours/create')
                        .then((res)=>{
                            this.filieres = res.data.filieres;
                            this.matieres = res.data.matieres;
                        })
                        .catch((err)=>console.error(err));
        },
        search(event){
            {
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredStudents = [...this.students];
                    } else {
                        this.filteredStudents = this.students.filter((student) => {
                            return student.name.toLowerCase().startsWith(event.query.toLowerCase());
                        });
                    }
                }, 250);
            }
        },
    },
  };
  </script>

<style scoped>

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
