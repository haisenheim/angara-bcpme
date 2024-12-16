<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES INSCRIPTIONS',path:'/super/inscriptions'}" :link_2="'NOUVELLE INSCRIPTION'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="name" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container">
            <form enctype="multipart/form-data" @submit.prevent="submit()">
                <div class="card">
                    <div class="card-body">
                    <div class="">
                        <div class="form-group mb-3 d-flex gap-3">
                            <div class="" style="width: 100px;">
                                <img class="img-thumbnail img-avatar img-circle" :src="src"  alt="">
                            </div>
                            <div class="align-content-center">
                                <AutoComplete placeholder="Nom de l'etudiant" pt:input:class="form-control" pt:input:style="min-width:300px; height:fit-content" v-model="student" optionLabel="name" :suggestions="filteredStudents" @complete="search">
                                    <template #option="slotProps">
                                        <div class="fs-6 d-flex gap-1">
                                            <img class="img-circle" :src="slotProps.option.photo" style="width: 25px;" alt="">
                                            <div>{{ slotProps.option.name }} - {{ slotProps.option.phone }}</div>
                                        </div>
                                    </template>
                                </AutoComplete>
                            </div>
                            <div class="align-content-center">
                                <router-link class="btn btn-primary" to="/etudiants/create"><i class="pli-add fs-5 me-1"></i> Creer l'etudiant</router-link>
                            </div>
                        </div>

                        <div class="d-flex gap-4 flex-wrap justify-content-center">
                            <div class="form-group">
                                <label for="">MATRICULE</label>
                                <input type="text" disabled v-model="student.matricule" placeholder="MATRICULE" class="form-control text-bold text-lg">
                            </div>
                            <div class="form-group">
                                <label for="">NOM</label>
                                <input type="text" disabled v-model="student.last_name" placeholder="NOM de l'etudiant" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">PRENOM</label>
                                <input disabled type="text" v-model="student.first_name" placeholder="NOM de l'etudiant" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">TUTEUR</label>
                                <input disabled type="text" v-model="tuteur"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">DATE DE NAISSANCE</label>
                                <input disabled type="date" v-model="student.dtn"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">LIEU DE NAISSANCE</label>
                                <input disabled type="text" v-model="student.lieu"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NOM DU PERE</label>
                                <input disabled type="text" v-model="student.pere"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NOM DE LA MERE</label>
                                <input disabled type="text" v-model="student.mere"  class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="">ADRESSE</label>
                                <input disabled type="text" v-model="student.address"  class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">TELEPHONE</label>
                                <input disabled type="text" v-model="student.phone" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">EMAIL</label>
                                <input disabled type="email" v-model="student.email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">NATIONALITE</label>
                                <input disabled type="email" v-model="student.pays" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">DERNIER DIPLOME OBTENU</label>
                                <input disabled type="email" v-model="student.diplome" class="form-control">
                            </div>
                        </div>
                        <fieldset class="mt-4">
                            <legend>Inscrire en ...</legend>
                            <div class="d-flex justify-content-center gap-5">
                                <div class="form-group">
                                    <label for="">FILIERE</label>
                                    <select required class="form-control" @change="selectFiliere($event)" id="">
                                        <option value="">Choix de la filiere </option>
                                        <option v-for="opt in filieres" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">NIVEAU</label>
                                    <select class="form-control" required @change="selectNiveau($event)" id="">
                                        <option value="">Choix du niveau </option>
                                        <option value="1">NIVEAU 1</option>
                                        <option value="2">NIVEAU 2</option>
                                        <option value="3">NIVEAU 3</option>
                                    </select>
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
  import AutoComplete from 'primevue/autocomplete';
  import avatar from '~/img/avatar.png';
  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
      AutoComplete
    },
    name: "CreateInscription",
    data() {
      return {
        title:'Nouvelle Inscription',
        description:'Formulaire de creation d\'une nouvelle inscription',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        inscription:{},
        filieres:[],
        students:[],
        student:{},
        filteredStudents:[],
        path:'',
      };
    },

    mounted(){
      this.load();
    },
    computed:{
        src(){
            if(this.student.matricule!=undefined){
                return `${this.student.photo}`
            }else{
                return avatar;
            }
        },
        name(){
            if(this.student.matricule!=undefined){
                return `${this.student.name}`
            }else{
                return "Nouvelle Inscription";
            }
        },
        tuteur(){
            if(this.student.tuteur!=undefined ){
                return `${this.student.tuteur.name} ${this.student.tuteur.phone}`
            }else{
                return "";
            }
        }
    },
    methods: {
      selectFiliere(event){
        let id = event.target.value;
        this.inscription.filiere_id = id;
      },
      selectNiveau(event){
        let id = event.target.value;
        this.inscription.niveau_id = id;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            this.inscription.etudiant_id = this.student.id;
            form.append('inscription',JSON.stringify(this.inscription))

            await this.api.post('/api/inscriptions',form)
                        .then((res)=>{
                            this.inscription = res.data;
                            this.toaster.success("Creation de l'inscription effectuee avec succes !!!");
                            this.$router.push({path:'/inscriptions'})
                           // this.$router.push({name:'super_inscription',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'inscription!");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){
            await this.api.get('/api/inscriptions/create')
                        .then((res)=>{
                            this.filieres = res.data.filieres;
                            this.students = res.data.students;
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
