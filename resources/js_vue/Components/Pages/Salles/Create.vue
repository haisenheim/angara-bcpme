<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES SALLES',path:'/salles'}" :link_2="'NOUVELLE SALLE'"></BreadCrumb>
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
                            <input type="text" v-model="salle.name" required placeholder="Nom de la salle" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                            <label for="">CODE</label>
                            <input type="text" v-model="salle.code" required placeholder="Code de la salle" class="form-control">
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
    name: "CreateSalle",
    data() {
      return {
        title:'Nouvelle Salle',
        description:'Formulaire de creation d\'une nouvelle salle',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        salle:{},
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
        this.salle.cycle_id = event.target.value;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('salle',JSON.stringify(this.salle))

            await this.api.post('/api/salles',form)
                        .then((res)=>{
                            this.salle = res.data;
                            this.toaster.success("Creation de la salle effectuee avec succes !!!");
                            this.$router.push({path:'/salles'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la salle");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){

        }
    },
  };
  </script>
