<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'EVALUATIONS',path:'/examens'}" :link_2="'LISTE DES EVALUATIONS'"></BreadCrumb>
        </template>
        <template v-slot:actions>
            <li><a data-bs-toggle="modal" data-bs-target="#modal" class="dropdown-item" href="#"><i class="pli-add"></i> Creer une evaluation</a></li>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'LISTE DES EVALUATIONS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <ag-grid-vue
                        :rowData="rowData"
                        :columnDefs="cols"
                        style="height: 300px"
                        class="ag-theme-balham"
                        :rowSelection="'single'"
                        :defaultColDef="defaultColDef"
                        @selection-changed="onRowSelected"
                        @grid-ready="onGridReady"
                        :pagination=true
                        :paginationPageSize=20
                        :paginationPageSizeSelector="paginationPageSizeSelector"
                        >
                    </ag-grid-vue>
                    <div id="modal" class="modal fade">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>CREATION D'UNE EVALUATION</h5>
                                    <button style="float:right;" data-bs-dismiss="modal" class="btn-xs btn btn-danger"><span class=" rounded-circle text-white">x</span></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group mt-3">
                                        <select @change="selectEval" name="" v-model="examen.evaluation_id" id="" class="form-control">
                                            <option value="0">TYPE D'EVALUATION ...</option>
                                            <option v-for="item in evaluations" :key="item.id" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-2">
                                        <div class="d-flex gap-1">
                                            <div>
                                                <input type="text" :value="evaluation.type.name" disabled placeholder="TYPE D'ACTIVITE" class="form-control">
                                            </div>
                                            <div>
                                                <input type="text" :value="evaluation.periodicite.name" disabled placeholder="PERIODICITE" class="form-control">
                                            </div>
                                            <div>
                                                <input type="text" :value="`${evaluation.pourcentage}%`" disabled placeholder="POURCENTAGE" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="examen.filiere_id" id="" class="form-control">
                                            <option value="0">FILIERE ...</option>
                                            <option v-for="item in filieres" :key="item.id" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="examen.semestre" id="" class="form-control">
                                            <option value="0">SEMESTRE...</option>
                                            <option v-for="item in [1,2,3,4,5,6]" :key="item" :value="item">SEMESTRE {{ item }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="examen.mois_id" id="" class="form-control">
                                            <option value="0">MOIS...</option>
                                            <option v-for="item in mois" :key="item.id" :value="item.id">{{ item.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group mt-3">
                                        <select name="" v-model="examen.cours_id" id="" class="form-control">
                                            <option value="0">MATIERE...</option>
                                            <option v-for="item in _cours" :key="item.id" :value="item.id">{{ item.matiere.name }}</option>
                                        </select>
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
            description:'Liste des evaluations',
            cours:[],
            mois:[],
            filieres:[],
            types:[],
            periodes:[],
            cour:{},
            evaluations:[],
            evaluation:{
                type:{},
                periodicite:{}
            },
            examens:[],
            examen:{
                evaluation_id:0,
                filiere_id:0,
                semestre:0,
                mois_id:0,
                cours_id:0,
            },
            type_id:0,
            periode_id:0,
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            paginationPageSizeSelector:[20, 50, 100],
            cols:[
                { field: "id",filter:false,hide:true },
                { field: "token",filter:false,hide:true },
                {field:'filiere',headerName:'Filiere',flex:1},
                {field:'semestre',headerName:'Semestre'},
                {field:'mois',headerName:'Mois'},
                {field:'matiere',headerName:'Matiere',flex:1},
                {field:'evaluation',headerName:'Intitule',flex:3},
                {field:'activite',headerName:'Activite',flex:2},
                {field:'periode',headerName:'Frequence',flex:1},
                {field:'pourcentage',headerName:'%',flex:1},

            ],
        }
    },
    computed:{
        _cours(){
            return this.cours.filter((item)=>item.filiere.id==this.examen.filiere_id&&item.semestre==this.examen.semestre);
        },
        rowData(){
            return this.examens.map(function(item){
                return {
                    id:item.id,
                    token:item.token,
                    filiere:item.filiere.code,
                    semestre:item.semestre,
                    matiere:item.cours.matiere.code,
                    mois:item.mois.name,
                    evaluation:item.evaluation.name,
                    activite:item.type.name,
                    periode:item.periodicite.name,
                    pourcentage:`${item.pourcentage}%`,
                }

            });
        }
    },
    methods:{
        onGridReady(params) {
            this.gridApi = params.api;
            let ga = params.api;
            this.emitter.emit('ready', {'gridApi':ga});
        },
        onRowSelected(event) {
                const rows = this.gridApi.getSelectedRows();
                console.log(rows[0]);
                let  token = rows[0].token;
                this.$router.push(`/examens/show/${token}`)
                //window.location.href=`/examens/${token}`;
        },
        selectEval(event){
            this.evaluation = this.evaluations.filter((item)=>item.id==event.target.value)[0];
            console.log(this.evaluation);
        },
        async submit(){
            let loader = this.$loading.show();

            await this.api.post('/api/examens',this.examen)
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
            await this.api.get('/api/examens')
            .then((res)=>{
                console.log(res.data);
                this.mois = res.data.mois;
                this.filieres = res.data.filieres;
                this.types = res.data.types;
                this.periodes = res.data.periodes;
                this.cours = res.data.cours;
                this.evaluations = res.data.evaluations;
                this.examens = res.data.examens;
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
