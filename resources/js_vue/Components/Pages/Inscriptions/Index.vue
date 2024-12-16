<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES INSCRIPTIONS',path:'/inscriptions'}" :link_2="'LISTE DES INSCRIPTIONS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'LISTE DES INSCRIPTIONS'" ></PageHeader>
            <div class="d-flex justify-content-between gap-3">
                
                <router-link to="/inscriptions/create" class="btn btn-danger btn-sm w-25"><i class="pli-add"></i> NOUVELLE INSCRIPTION</router-link>
            </div>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="mt-4">
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
            </div>
        </template>
    </Display>

</template>

<script>
export default {
    name:"InscriptionIndex",
    data(){
        return {
            user:this.$store.state.user,
            description:'Historique des inscriptions de l\'annee en cours',
            inscriptions:[],
            filtered:[],
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            pagination:true,
            paginationPageSize:20,
            paginationPageSizeSelector:[20, 50, 100],
            cols:[
                { field: "id",filter:false,hide:true },
                { field: "token",filter:false,hide:true },
                {field:'date',headerName:'Date',flex:1},
                {field:'name',headerName:'Etudiant',},
                {field:'matricule',headerName:'Matricule',flex:1},
                {field:'filiere',headerName:'Filiere',flex:2},
                {field:'niveau',headerName:'Niveau'},
            ],
        }
    },
    computed:{
        rowData(){
            return this.inscriptions.map(function(item){
                return {
                    id:item.id,
                    token:item.token,
                    date:item.date,
                    filiere:item.filiere.name,
                    niveau:`NIVEAU ${item.niveau_id}`,
                    matricule:item.etudiant.matricule,
                    name:item.etudiant.name,
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
      async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/inscriptions')
            .then((res)=>{
                console.log(res.data);
                this.inscriptions = res.data;
                this.filtered = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
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
