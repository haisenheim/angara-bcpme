<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES EMPLOIS DE TEMPS',path:'/emplois'}" :link_2="'LISTE DES TRANCHES HORAIRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES EMPLOIS DE TEMPS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div>
                        <div class="bar">
                            <div class="d-flex justify-content-center gap-3 mb-3 flex-wrap">
                                <div class="form-group">
                                    <label for="">FILIERE</label>
                                    <select @change="selectFiliere" class="form-control">
                                        <option v-for="filiere in filieres" :key="filiere.id" :value="filiere.id">{{filiere.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">SEMESTRE</label>
                                    <select @change="selectSemestre" style="min-width: 200px;" class="form-control">
                                        <option v-for="n in [1,2,3,4,5,6]" :key="n" :value="n">SEMESTRE {{n}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">MATIERE</label>
                                    <select @change="selectMatiere" style="min-width: 200px;" class="form-control">
                                        <option v-for="cr in cours" :key="cr.id" :value="cr.id">{{ cr.matiere.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">ENSEIGNANT</label>
                                    <select @change="selectEnseignant" style="min-width: 200px;" class="form-control">
                                        <option v-for="ens in enseignants" :key="ens.id" :value="ens.id">{{ ens.name }}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="d-flex justify-content-between gap-3">
                                <div class="form-group">
                                    <select class="form-control" v-model="day">
                                        <option v-for="day in weekDays" :key="day" :value="day">{{day}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="heure de debut" v-model="emploi.start" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="heure de fin" v-model="emploi.end" />
                                </div>
                                <div class="form-group">
                                    <select @change="selectSalle" style="min-width: 200px;" class="form-control">
                                        <option value="0">Salle ...</option>
                                        <option v-for="ens in salles" :key="ens.id" :value="ens.id">{{ ens.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Cout horaire de la prestation" v-model="emploi.pu" />
                                </div>
                                <div>
                                    <button @click="submit" class="btn btn-primary btn-sm"><i class="pli-add"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card mt-2">
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
                    :pagination="pagination"
                    :paginationPageSize="paginationPageSize"
                    :paginationPageSizeSelector="paginationPageSizeSelector"
                    >
                    </ag-grid-vue>
                </div>
            </div>
        </template>
    </Display>

</template>

<script>

export default {
    name:"EmploisIndex",
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des emplois de temps',
            weekDays: ['LUNDI','MARDI','MERCREDI','JEUDI','VENDREDI','SAMEDI','DIMANCHE'],
            day: 'LUNDI',
			length: '15',
			emplois: [],
            filtered:[],
            emploi:{
                start: '8:00',
                end: '10:30',
                day: 'LUNDI',
            },
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },

            cours:[],
			filieres: [],
            enseignants:[],
            filiere_id:0,
            semestre:0,
            salles:[],
            cols:[
                { field: "id",filter:false,hide:true },
                { field: "token",filter:false,hide:true },
                {field:'filiere',headerName:'Filiere',flex:1},
                {field:'semestre',headerName:'Semestre',},
                {field:'day',headerName:'Jour',flex:1},
                {field:'matiere',headerName:'Matiere',flex:1},
                {field:'start',headerName:'Debut'},
                {field:'end',filter:true,headerName:'Fin'},
                {field:'salle'},
                {field:'enseignant',flex:2},
                {field:'taux'},
                {field:'hrs'},
                {field:'seances'}
            ],
            pagination:true,
            paginationPageSize:20,
            paginationPageSizeSelector:[20, 50, 100],
        }
    },
    computed:{
        rowData(){
            return this.emplois.map(function(item){
                return {
                    id:item.id,
                    filiere:item.filiere.code,
                    semestre:item.semestre,
                    day:item.day,
                    matiere:item.matiere.code,
                    start:item.start,
                    end:item.end,
                    salle:item.salle.code,
                    enseignant:item.enseignant.last_name+" "+item.enseignant.first_name,
                    taux:item.pu,
                    token:item.token,
                    hrs:item.thr,
                    seances:item.ts

                }
            })
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
            let  token = rows[0].token;
            this.$router.push(`/emplois/${token}`)
            //window.location.href=`/emplois/${token}`;
    },
      selectFiliere(event){
        this.filiere_id = event.target.value;
        this.emploi.filiere_id = event.target.value;
      },
      selectEnseignant(event){
        this.emploi.enseignant_id = event.target.value;
      },
      selectMatiere(event){
        this.emploi.cours_id = event.target.value;
      },
      selectSalle(event){
        this.emploi.salle_id = event.target.value;
      },
      async load(){
        await this.api.get('/api/emplois/create')
            .then((res)=>{
                console.log(res.data);
                this.filieres = res.data.filieres;
                this.enseignants = res.data.enseignants;
                this.emplois = res.data.emplois;
                this.filtered = res.data.emplois;
                this.salles = res.data.salles;
                this.emploi.enseignant_id = this.enseignants[0].id;
                this.emploi.salle_id = this.salles[0].id;
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
            this.emploi.day = this.day;
            await this.api.post('/api/emplois',this.emploi)
            .then((res)=>{
                console.log(res.data);
                this.toaster.success("Ajout effectue avec succes !!!");
                this.load();

            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
            })
      },
      async selectSemestre(event){
        this.semestre = event.target.value;
        this.emploi.semestre = event.target.value;
        await this.api.post('/api/emploi/cours',{'filiere_id':this.filiere_id,'semestre':this.semestre})
            .then((res)=>{
                console.log(res.data);
                this.cours = res.data;
                this.emploi.cours_id = this.cours[0].id;

            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        },
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>
    .form-control{
        padding:.3rem 1rem;
    }

    .form-control:focus{
        border-color: #ef940a;
    }

    .ag-theme-balham {
    /* Changes the color of the grid text */
      --ag-foreground-color: #25476a;
      /* Changes the color of the grid background */
      --ag-background-color: rgb(241, 247, 255);
      /* Changes the header color of the top row */
      --ag-header-background-color: rgb(228, 237, 250);
      /* Changes the hover color of the row*/
      --ag-row-hover-color: rgb(192, 206, 249);
}

</style>
