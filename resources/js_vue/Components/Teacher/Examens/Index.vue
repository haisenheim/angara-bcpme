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
                </div>
            </div>
        </template>
    </Display>

</template>

<script>
export default {
    name:"ExamsIndex",

    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des evaluations',
            examens:[],
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
                this.$router.push(`/prof/examens/show/${token}`)
                //window.location.href=`/examens/${token}`;
        },
      async load(){
            await this.api.get('/api/prof/examens')
            .then((res)=>{
                this.examens = res.data;
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
