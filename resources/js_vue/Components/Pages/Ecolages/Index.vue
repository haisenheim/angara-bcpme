<template>
    <Display ref="index"  :search="true">
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES FRAIS DE SCOLARITE',path:'/ecolages'}" :link_2="'FRAIS DE SCOLARITE'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'Historiques des paiements'" ></PageHeader>
            <div class="d-flex justify-content-between gap-3">
                
            <router-link to="/ecolages/create" class="btn btn-danger btn-sm w-25"><i class="pli-add"></i> Nouveau Paiement</router-link>
            </div>
        </template>
        <template v-slot:content>
            <div class="card" style="min-width: 400px;">
                <div class="card-body">
                    <ag-grid-vue
                    :rowData="rowData"
                    :columnDefs="cols"
                    style="height: 320px"
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
import { createToaster } from "@meforma/vue-toaster";
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';

import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-balham.css"; // Optional Theme applied to the grid
import { AgGridVue } from "ag-grid-vue3";

export default {
    name:"EmploisIndex",
    components:{
        Display,
        PageHeader,
        BreadCrumb,
        AgGridVue,
    },
    data(){
        return {
            user:this.$store.state.user,
            toaster: createToaster({ position:'top-right'}),
            description:'Frais de scolarite mensuelle de l\'etudiant',

            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            ecolages:[],
            cols:[
                {field:"id",filter:false,hide:true },
                {field:"token",filter:false,hide:true},
                {field:'created',headerName:'Date',flex:1},
                {field:'etudiant',headerName:'Etudiant',flex:2},
                {field:'matricule',headerName:'Matricule',flex:1},
                {field:'classe',headerName:'Classe',flex:1},
                {field:'mois',headerName:'Mois',flex:1},
                {field:'montant',headerName:'Montant'},
                {field:'username',headerName:'Operateur',flex:2},
            ],
            pagination:true,
            paginationPageSize:20,
            paginationPageSizeSelector:[20,100, 500, 2000],
        }
    },
    computed:{
        rowData(){
            return this.ecolages.map(function(item){
                return {
                    id:item.id,
                    created:item.created,
                    mois:item.mois.name,
                    montant:item.montant,
                    etudiant:item.etudiant.name,
                    matricule:item.etudiant.matricule,
                    classe:`${item.filiere.code} ${item.niveau_id}`,
                    username:item.user.name,
                    token:item.token,
                }
            })
        },
    },
    methods:{
        onGridReady(params) {
            this.gridApi = params.api;
            let ga = params.api;
            this.emitter.emit('ready', {'gridApi':ga});
        },
        onRowSelected(event) {
            console.log(event);
        },
        onFilterTextBoxChanged() {
            this.gridApi.setGridOption(
                "quickFilterText",
                document.getElementById("filter-text-box").value,
            );
        },
    async load(){
        console.log(this.refs);
        let loader = this.$loading.show();
        await this.api.get('/api/ecolages')
            .then((res)=>{
                console.log(res.data);
                this.ecolages = res.data;

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
    input.rounded-2{
        margin-left: 5px;
        background: #c6dff9;
    }

    .infos .form-group{
        align-content: center;
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
