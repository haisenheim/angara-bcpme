<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES POSTS',path:'/posts'}" :link_2="'LISTE DES POSTS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES POSTS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h2>GESTION DES POSTS</h2>
                        <router-link class="btn btn-primary" to="/posts/create"><i class="demo-pli-add me-1 fs-5"></i> NOUVEAU POST</router-link>
                    </div>
                    <div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>DATE</th>
                                    <th>INTITULE</th>
                                    <th>CATEGORIE</th>
                                    <th>STATUS</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts" :key="post.id">
                                    <td>{{ post.date }}</td>
                                    <td>{{ post.name }}</td>
                                    <td>{{ post.category.name }}</td>
                                    <td><span :class="post.status.color" class="badge">{{ post.status.name }}</span></td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </template>
    </Display>

</template>

<script>
export default {
    name:"PostIndex",
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des posts de l\'ecole',
            posts:[],
        }
    },
    methods:{
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/posts')
            .then((res)=>{
                console.log(res.data);
                this.posts = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        }
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>

</style>
