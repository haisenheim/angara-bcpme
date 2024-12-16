<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'ENSEIGNANTS',path:'/enseignants'}" :link_2="'LISTE DES ENSEIGNANTS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'BASE DE DONNEES DES ENSEIGNANTS'" ></PageHeader>
        </template>
        <template v-slot:actions>
            <li><router-link class="dropdown-item" to="/enseignants/create"><i class="demo-pli-add me-1 fs-5"></i> NOUVEL ENSEIGNANT</router-link></li>
        </template>
        <template v-slot:content>
            <div class="">
                <div class="">
                    <div class="card" style="min-width: 400px;">
                        <div class="card-body">
                            <ag-grid-vue
                            :rowData="rowData"
                            :columnDefs="cols"
                            :rowHeight="'50px'"
                            style="height: 320px"
                            class="ag-theme-balham"
                            :rowSelection="'single'"
                            :defaultColDef="defaultColDef"
                            @selection-changed="onRowSelected"
                            @grid-ready="onGridReady"
                            :pagination=true
                            :paginationPageSize="paginationPageSize"
                            :paginationPageSizeSelector="paginationPageSizeSelector"
                            >
                            </ag-grid-vue>
                        </div>
                    </div>


                </div>
            </div>
        </template>
    </Display>

</template>

<script>
import Photo from '@/Components/AgGrid/Photo.vue';
import avatar from '~/img/avatar.png';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-balham.css"; // Optional Theme applied to the grid
export default {
    name:"SuperEnseignantIndex",
    components:{
        Photo,
    },
    computed:{
        rowData(){
            return this.enseignants.map(function(item){
                return {
                    id:item.id,
                    nom:item.last_name,
                    prenom:item.first_name,
                    nationalite:item.pays,
                    age:item.age +'ans',
                    photo:item.photo,
                    adresse:item.address,
                    telephone:item.phone,
                    diplome:item.diplome,
                    email:item.email,
                    token:item.token,
                }
            })
        },
    },
    data(){
        return {
            user:this.$store.state.user,
            description:'BASE DE TOUS LES ENSEIGNANTS',
            enseignants:[],
            filtered:[],
            gridApi: null,

            defaultColDef: {
                flex: 3,
                filter:true,
                floatingFilter:true,
            },
            cols:[
                {field:"id",filter:false,hide:true },
                {field:"token",filter:false,hide:true},
                {
                    headerName: "#",
                    field: "photo",
                    flex:1,
                    cellRenderer: "Photo",
                    cellClass: "logoCell",
                    minWidth: 100,
                },
                {field:'nom',},
                {field:'prenom'},
                {field:'telephone'},
                {field:'email',flex:5},
                {field:'adresse'},
                {field:'age',flex:2},
                {field:'nationalite',flex:2},
                {field:'diplome',headerName:'Niveau'}

            ],
            paginationPageSize:20,
            paginationPageSizeSelector:[20,100, 500, 2000],
        }
    },
    methods:{
        onGridReady(params) {
            this.gridApi = params.api;
            let ga = params.api;
            this.emitter.emit('ready', {'gridApi':ga});
        },
        onRowSelected(event) {
            console.log(event);
            const rows = this.gridApi.getSelectedRows();
            let  token = rows[0].token;
            //this.$router.push(`/enseignants/show/${token}`)
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/enseignants')
            .then((res)=>{
                console.log(res.data);
                this.enseignants = res.data;
                this.filtered = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        }
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>
    td{
        vertical-align:middle
    }
    .enseignant{
        min-width: 200px;
    }
</style>
