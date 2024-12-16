<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES ANNEES',path:'/annees'}" :link_2="'LISTE DES ANNEES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES ANNEES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card mb-3">
                <div class="card-body">
                    <div>
                        <form @submit.prevent="submit">
                            <fieldset>
                                <legend class="text-sm">Nouvelle annee academique</legend>
                                <div class="d-flex gap-3 mb-2">
                                    <div class="form-group">
                                        <label for="">Date de la Rentree</label>
                                        <input type="date" class="form-control" v-model="annee.day" style="min-width:200px">
                                    </div>
                                    <div class="form-group">
                                        <label for="">ANNEE DE DEPART</label>
                                        <input type="number" class="form-control" v-model="annee.start">
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary mt-3"><i class="pli-add"></i></button>
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
                        <div class="mb-2">
                            <input @input="search" type="text" style="max-width: 400px;" class="form-control bg-light p-1 border-white" placeholder="Rechercher ...">
                        </div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ANNEE ACADEMIQUE</th>
                                    <th>RENTREE</th>
                                    <th>STATUT</th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="annee in annees" :key="annee.id">
                                    <td>{{ annee.start }}-{{ annee.end}}</td>
                                    <td>{{ annee.day }}</td>
                                    <td><span class="badge" :class="annee.status.class">{{ annee.status.name }}</span></td>
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
            user:this.$store.state.user,
            description:'Liste des annees academiques',
            annees:[],
            annee:{},
        }
    },
    computed:{
    },
    methods:{
        search(event){
            console.log(event.target.value);
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.annees];
                    } else {
                        this.filtered = this.annees.filter((item) => {
                            return item.begin.toLowerCase().startsWith(event.target.value.toLowerCase())

                        });
                    }
                }, 250);
            }
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/annees')
            .then((res)=>{
                console.log(res.data);
                this.annees = res.data;
                this.annees.map((item)=>{
                    if(item.active==1){
                        item.status = {class:'bg-success',name:'en cours'}
                    }else{
                        item.status = {class:'bg-danger',name:'passée'}
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
            await this.api.post('/api/annees',this.annee)
            .then((res)=>{
                console.log(res.data);
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
