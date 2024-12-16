<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'ETUDIANTS',path:'/etudiants'}" :link_2="'LISTE DES ETUDIANTS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'BASE DE DONNEES DES ETUDIANTS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="">
                <div class="card" style="min-width: 400px;">
                        <div class="card-body">
                            <ag-grid-vue
                            :rowData="rowData"
                            :columnDefs="cols"
                            :rowHeight="'50px'"
                            style="height: 400px"
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
        </template>
    </Display>

</template>

<script>
import Photo from '@/Components/AgGrid/Photo.vue';
import avatar from '~/img/avatar.png';
export default {
    name:"SuperEtudiantIndex",
    components:{
        Photo,
    },
    computed:{
        rowData(){
            return this.etudiants.map(function(item){
                return {
                    id:item.id,
                    nom:item.last_name,
                    prenom:item.first_name,
                    nationalite:item.pays,
                    age:item.age +'ans',
                    photo:item.photo,
                    adresse:item.address,
                    telephone:item.phone,
                    classe:item.classe,
                    matricule:item.matricule,
                    email:item.email,
                    tuteur:item.tuteur?item.tuteur.name:'-',
                    token:item.token,
                }
            })
        },
    },
    data(){
        return {
            user:this.$store.state.user,
            description:'CATALOGUE DE TOUS LES ETUDIANTS',
            etudiants:[],
            filtered:[],
            avatar: avatar,
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
                {field:'matricule',flex:2},
                {field:'nom',},
                {field:'prenom'},
                {field:'telephone'},
                {field:'tuteur'},
                {field:'age',flex:2},
                {field:'nationalite',flex:2},
                {field:'classe',headerName:'Classe'}

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
            //this.$router.push(`/etudiants/show/${token}`)
        },
        search(event){
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.etudiants];
                    } else {
                        this.filtered = this.etudiants.filter((item) => {
                            return item.last_name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.first_name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.matricule.toLowerCase().startsWith(event.target.value.toLowerCase())

                        });
                    }
                }, 250);
            }
        },
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/etudiants')
            .then((res)=>{
                console.log(res.data);
                this.etudiants = res.data;
                this.filtered = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
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
    .etudiant{
        min-width: 200px;
    }
</style>
