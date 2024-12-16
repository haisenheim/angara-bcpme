<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DE LA BIBLIOTHEQUE',path:'/bibliotheque'}" :link_2="livre.name"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="description" :h1="title" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="container">
            <div style="width: 700px;">
                <VuePdfEmbed :source="livre.path" />
            </div>
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
  import VuePdfEmbed from 'vue-pdf-embed'
  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
      VuePdfEmbed,
    },
    props:{
        tkn:String
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
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      async load(){
        this.livre = JSON.parse(this.tkn);
        }
    },
  };
  </script>

<style scoped>

  .form-group{
    margin-top: 20px;
  }

  canvas{
  }
</style>
