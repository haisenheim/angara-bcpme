<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES LABORATOIRES',path:'/laboratoires'}" :link_2="'NOUVEAU LABORATOIRE'"></BreadCrumb>
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
                            <input type="text" v-model="laboratoire.name" required placeholder="Nom du laboratoire" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                            <label for="">CODE</label>
                            <input type="text" v-model="laboratoire.code" required placeholder="Code du laboratoire" class="form-control">
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
    name: "CreateLaboratoire",
    data() {
      return {
        title:'Nouveau Laboratoire',
        description:'Formulaire de creation d\'un nouveau laboratoire',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        laboratoire:{},
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
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('laboratoire',JSON.stringify(this.laboratoire))

            await this.api.post('/api/laboratoires',form)
                        .then((res)=>{
                            this.laboratoire = res.data;
                            this.toaster.success("Creation du laboratoire effectuee avec succes !!!");
                            this.$router.push({path:'/laboratoires'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation du laboratoire");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){

        }
    },
  };
  </script>
