<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'MON EMPLOI DE TEMPS',path:'#'}" :link_2="'LISTE DES TRANCHES HORAIRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'MON EMPLOI DE TEMPS'" ></PageHeader>
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

			length: '15',
			items: [],
            filtered:[],
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
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
            return this.items.map(function(item){
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
            this.$router.push(`/prof/emplois/${token}`)
    },
      async load(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
        await this.api.get('/api/prof/emplois')
            .then((res)=>{
                console.log(res.data);
                this.items = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
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
