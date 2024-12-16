<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TARIFS',path:'/tarifs'}" :link_2="'LISTE DES TARIFS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES TARIFS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card mb-3">
                <div class="card-body">
                    <div>
                        <form @submit.prevent="submit">
                            <fieldset>
                                <legend class="text-sm">Nouveau tarif </legend>
                                <div class="d-flex justify-content-center gap-5">
                                <div class="form-group">
                                    <label for="">FILIERE</label>
                                    <select required class="form-control" @change="selectFiliere($event)" id="">
                                        <option value="">Choix de la filiere </option>
                                        <option v-for="opt in filieres" :key="opt.id" :value="opt.id">{{ opt.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">NIVEAU</label>
                                    <select class="form-control" required @change="selectNiveau($event)" id="">
                                        <option value="">Choix du niveau</option>
                                        <option value="1">NIVEAU 1</option>
                                        <option value="2">NIVEAU 2</option>
                                        <option value="3">NIVEAU 3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">MENSUALITE</label>
                                    <input type="number" v-model="tarif.mensualite" placeholder="Montant mensuel" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">INSCRIPTION</label>
                                    <input type="number" v-model="tarif.inscription" placeholder="Cout de l'inscription" required class="form-control">
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
                                    <th>FILIERE</th>
                                    <th>NIVEAU</th>
                                    <th>MENSUALITE</th>
                                    <th>INSCRIPTION</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tarif in tarifs" :key="tarif.id">
                                    <td>{{ tarif.filiere.name }}</td>
                                    <td>{{ tarif.niveau_id }}</td>
                                    <td>{{ tarif.mensualite }}</td>
                                    <td>{{ tarif.inscription }}</td>
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
    name:"TarifIndex",
    components:{
        Display,
        PageHeader,
        BreadCrumb,
    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des tarifs',
            tarifs:[],
            tarif:{},
            filieres:[],
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
                        this.filtered = [...this.tarifs];
                    } else {
                        this.filtered = this.tarifs.filter((item) => {
                            return item.filiere.name.toLowerCase().startsWith(event.target.value.toLowerCase())

                        });
                    }
                }, 250);
            }
        },
        selectFiliere(event){
            let id = event.target.value;
            this.tarif.filiere_id = id;
        },
        selectNiveau(event){
            let id = event.target.value;
            this.tarif.niveau_id = id;
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/tarifs')
            .then((res)=>{
                console.log(res.data);
                this.tarifs = res.data.tarifs;
                this.filieres = res.data.filieres;
                this.tarifs.map((item)=>{
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
            await this.api.post('/api/tarifs',this.tarif)
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
