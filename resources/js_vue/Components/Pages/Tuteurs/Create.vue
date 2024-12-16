<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TUTEURS',path:'/tuteurs'}" :link_2="'NOUVEAU TUTEUR'"></BreadCrumb>
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
                            <input type="text" v-model="tuteur.name" required placeholder="Nom du tuteur" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">ADRESSE</label>
                            <input type="text" v-model="tuteur.address" placeholder="Adresse" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">TELEPHONE</label>
                            <input type="text" v-model="tuteur.phone" required placeholder="Telephone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">EMAIL</label>
                            <input type="text" v-model="tuteur.email" placeholder="Adresse email" class="form-control">
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
    name: "CreateTuteur",
    data() {
      return {
        title:'Nouveau Tuteur',
        description:'Formulaire de creation d\'un nouveau tuteur',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        tuteur:{},
        cycles:[
            {
                id:1,
                name:'Polytech'
            },
            {
                id:2,
                name:'Commerce'
            }
        ],
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      selectCyclee(event){
        this.tuteur.cycle_id = event.target.value;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('tuteur',JSON.stringify(this.tuteur))

            await this.api.post('/api/tuteurs',form)
                        .then((res)=>{
                            this.tuteur = res.data;
                            this.toaster.success("Creation de la tuteur effectuee avec succes !!!");
                            this.$router.back();
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la tuteur");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){

        }
    },
  };
  </script>

<style scoped>
    .form-group{
        margin-top:20px;
    }
</style>
