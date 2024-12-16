<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'Notes',path:'/notes'}" :link_2="'LISTE DES NOTES'"></BreadCrumb>
        </template>
        <template v-slot:actions>
            <li><a data-bs-toggle="modal" data-bs-target="#modal" class="dropdown-item" href="#"><i class="pli-add"></i> Creer une evaluation</a></li>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'TABLEAU GLOBAL DES NOTES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <ag-grid-vue
                        :rowData="notes"
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
    name:"EvaluationIndex",

    data(){
        return {
            user:this.$store.state.user,
            description:'Liste de toutes les notes',
            notes:[],
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            paginationPageSizeSelector:[20, 50, 100],
            cols:[
                { field: "id",filter:false,hide:true },
                {field:'filiere',headerName:'Filiere',flex:1},
                {field:'semestre',headerName:'Semestre'},
                {field:'mois',headerName:'Mois'},
                {field:'etudiant',headerName:'Etudiant',flex:2},
                {field:'note',headerName:'Note'},
                {field:'matiere',headerName:'Matiere',flex:2},
                {field:'activite',headerName:'Activite',flex:2},
                {field:'periode',headerName:'Frequence',flex:1},
                {field:'pourcentage',headerName:'%',flex:1},

            ],
        }
    },
    computed:{
    
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
               
        },
       
      async load(){
            await this.api.get('/api/notes')
            .then((res)=>{
                console.log(res.data);
                this.notes = res.data;
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
