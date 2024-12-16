<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TRANCHES HORAIRES',path:'/tranches'}" :link_2="'NOUVELLE TRANCHE HORAIRE'"></BreadCrumb>
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
                            <label for="">DEBUT</label>
                            <input type="text" v-model="tranche.start" required placeholder="Heure de debut" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                            <label for="">FIN</label>
                            <input type="text" v-model="tranche.end" required placeholder="Heure de fin" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                            <label for="">DUREE (en heure(s))</label>
                            <input type="text" v-model="tranche.gap" required placeholder="exple 2.5 ou 3" class="form-control">
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
    name: "CreateTranche",
    data() {
      return {
        title:'Nouvelle Tranche',
        description:'Formulaire de creation d\'une nouvelle tranche',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        tranche:{},
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      selectCyclee(event){
        this.tranche.cycle_id = event.target.value;
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            form.append('tranche',JSON.stringify(this.tranche))

            await this.api.post('/api/tranches',form)
                        .then((res)=>{
                            this.tranche = res.data;
                            this.toaster.success("Creation de la tranche effectuee avec succes !!!");
                            this.$router.push({path:'/tranches'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la tranche");
                        })
                        .finally(()=>loader.hide());
      },
      async load(){

        }
    },
  };
  </script>
