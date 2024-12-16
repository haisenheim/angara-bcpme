<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES POSTS',path:'/posts'}" :link_2="'NOUVELLE POST'"></BreadCrumb>
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
                        <div class="d-flex gap-2">
                            <div class="" style="width: 100px;">
                                <img class="img-thumbnail img-avatar img-circle" :src="path"  alt="">
                            </div>
                            <div class="align-content-center" style="min-width: 100px;">
                                <label for="">PHOTO DE L'ETUDIANT</label>
                                <input type="file"  @change="handlePhoto($event)" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group mt-5">
                            <label for="">INITULE</label>
                            <input type="text" v-model="post.name" required placeholder="Intitule du post" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                            <label for="">CORPS DU POST</label>
                            <textarea v-model="post.description" required placeholder="Corps du post" class="form-control"></textarea>
                        </div>
                        <div class="form-group mt-4">
                            <label for="">CATEGORIE</label>
                            <select name="" class="form-control" @change="selectCategory($event)" id="">
                                <option value="">CATEGORIE ...</option>
                                <option v-for="opt in categories" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
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
  import avatar from '~/img/avatar.png';
  export default {
    name: "CreatePost",
    data() {
      return {
        title:'Nouveau Post',
        description:'Formulaire de creation d\'un nouveau post',
        post:{},
        photo:null,
        categories:[],
        path:avatar,
      };
    },

    mounted(){
      this.load();
    },
    methods: {
      selectCategory(event){
        this.post.category_id = event.target.value;
      },
      handlePhoto(event){
        this.photo = event.target.files[0];
        this.path = URL.createObjectURL(this.photo)
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            if(this.photo!=null){
                //console.log(this.photo);
                form.append('photo',this.photo);
                form.append('post',JSON.stringify(this.post))
                await this.api.post('/api/posts',form,this.multipart)
                        .then((res)=>{
                            this.post = res.data;
                            this.toaster.success("Creation de la post effectuee avec succes !!!");
                            this.$router.push({path:'/posts'})
                           // this.$router.push({name:'super_ecole',params:{tkn:this.dossier.token}})
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de la post");
                        })
                        .finally(()=>loader.hide());
            }else{
                this.toaster.error("Le champ de la photo ne peut etre vide !")
            }


      },
      async load(){
            await this.api.get('/api/posts/create')
                .then((res)=>{
                    console.log(res.data.categories)
                    console.log(this.categories)
                    this.categories = res.data.categories
                    console.log(this.categories)
                })
                .catch((err)=>console.log(err))
        }
    },
  };
  </script>
