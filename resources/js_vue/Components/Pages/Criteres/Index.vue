<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES CRITERES',path:'/criteres'}" :link_2="'LISTE DES CRITERES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'CRITERES D\'EVALUATION DES COURS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card mb-3">
                <div class="card-body">
                    <div>
                        <form @submit.prevent="submit">
                            <fieldset>
                                <legend class="text-sm">Nouveau critere</legend>
                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-group">
                                        <label for="">CRITERE</label>
                                        <input type="text" placeholder="Saisir le critere ici ..." class="form-control" v-model="critere.name" style="min-width:200px">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm mt-3"><i class="pli-add"></i></button>
                                    </div>
                            </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>CRITERE</th>
                                    <th>STATUT</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in criteres" :key="item.id">
                                    <td>{{ item.name }}</td>
                                    <td><span class="badge" :class="item.status.class">{{ item.status.name }}</span></td>
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
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
export default {
    name:"AnneeIndex",
    components:{
        Display,
        PageHeader,
        BreadCrumb,

    },
    data(){
        return {
            description:'Liste des criteres',
            criteres:[],
            critere:{},
        }
    },
    computed:{
    },
    methods:{
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/criteres')
            .then((res)=>{
                console.log(res.data);
                this.criteres = res.data;
                this.criteres.map((item)=>{
                    if(item.active==1){
                        item.status = {class:'bg-success',name:'actif'}
                    }else{
                        item.status = {class:'bg-danger',name:'desactivé'}
                    }
                    return item;
                })
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        },
        async submit(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.post('/api/criteres',this.critere)
            .then((res)=>{
                console.log(res.data);
                this.critere.name=''
                this.load()
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide()
            })

        }
    },
    mounted(){
        this.load();
    }
}
</script>

<style scoped>
    legend{
        font-size: 11px;
    }
</style>
