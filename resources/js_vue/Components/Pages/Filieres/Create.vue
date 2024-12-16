<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES FILIERES',path:'/filieres'}" :link_2="'NOUVELLE FILIERE'"></BreadCrumb>
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
                            <input type="text" v-model="filiere.name" required placeholder="Nom de la filiere" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="">CODE</label>
                            <input type="text" v-model="filiere.code" required placeholder="Code de la filiere" class="form-control">
                        </div>
                        <div class="form-group mt-2">
                            <label for="">CYCLE</label>
                            <select name="" class="form-control" @change="selectCycle($event)" id="">
                                <option value="">CYCLE</option>
                                <option v-for="opt in cycles" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                            </select>
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
    name: "CreateFiliere",
    data() {
      return {
        title:'Nouvelle Filiere',
        description:'Formulaire de creation d\'une nouvelle filiere',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        filiere:{},
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
      selectCycle(event){
        this.filiere.cycle_id = event.target.value;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('filiere',JSON.stringify(this.filiere))

            await this.api.post('/api/filieres',form)
                        .then((res)=>{
                            this.filiere = res.data;
                            this.toaster.success("Creation de la filiere effectuee avec succes !!!");
                            this.$router.push({path:'/filieres'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la filiere");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){

        }
    },
  };
  </script>
