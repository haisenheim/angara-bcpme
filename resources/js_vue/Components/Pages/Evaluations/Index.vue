<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'TYPES D\'EVALUATION',path:'/evaluations'}" :link_2="'LISTE DES TYPES D\'EVALUATION'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'LISTE DES TYPES EVALUATION'" ></PageHeader>
            <div class="d-flex justify-content-between mb-1">
                <a data-bs-toggle="modal" data-bs-target="#modal" class="btn btn-xs btn-danger" href="#"><i class="pli-add"></i> CREER UN TYPE D'EVALUATION</a>
            </div>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>INTITULE</th>
                                    <th>ACTIVITE</th>
                                    <th>PERIODICITE</th>
                                    <th>% DE LA NOTE GLOBALE</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in evaluations" :key="item.id">
                                    <td>{{ item.name }}</td>
                                    <td>{{ item.type.name }}</td>
                                    <td>{{ item.periodicite.name }}</td>
                                    <td>{{ item.pourcentage }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="modal" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button style="float:right;" data-bs-dismiss="modal" class="btn-xs btn btn-danger"><span class=" rounded-circle text-white">x</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mt-2">
                                            <input  type="text" v-model="evaluation.name" placeholder="Intitule de l'evaluation" class="form-control">
                                        </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="evaluation.type_id" id="" class="form-control">
                                            <option value="0">TYPE D'ACTIVITE ...</option>
                                            <option v-for="item in types" :key="item.id" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="evaluation.periodicite_id" id="" class="form-control">
                                            <option value="0">PERIODICITE ...</option>
                                            <option v-for="item in periodes" :key="item.id" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <input type="number" v-model="evaluation.pourcentage" placeholder="Pourcentage de la note semestrielle globale" class="form-control">
                                    </div>
                                    <div class="form-group mt-3">
                                        <button @click="submit" data-bs-dismiss="modal" class="btn btn-primary btn-sm"><i class="pli-storage-yes"></i> ENREGISTRER </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </Display>

</template>

<script>
export default {
    name:"EvaluationIndex",

    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des types de l\'evaluation',
            types:[],
            mois:[],
            periodes:[],
            evaluations:[],
            evaluation:{
                periodicite_id:0,
                type_id:0,
                name:'',
                pourcentage:0,
            }
        }
    },
    methods:{
        async submit(){
            let loader = this.$loading.show();
            await this.api.post('/api/evaluations',this.evaluation)
            .then((res)=>{
                console.log(res.data);
                this.load();
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
            })

        },
      async load(){
            await this.api.get('/api/evaluations')
            .then((res)=>{
                console.log(res.data);
                this.mois = res.data.mois;
                this.types = res.data.types;
                this.periodes = res.data.periodes;
                this.evaluations = res.data.evaluations;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
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
