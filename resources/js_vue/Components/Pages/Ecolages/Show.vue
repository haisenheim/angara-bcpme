<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES EMPLOIS DE TEMPS',path:'/emplois'}" :link_2="title"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="title" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="d-flex gap-3">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <div class="bar">
                                <div class="d-flex justify-content-center gap-3 mb-3 flex-wrap">
                                    <div class="form-group">
                                        <label for="">FILIERE</label>
                                        <input type="text" class="form-control form-control-lg" disabled v-model="emploi.filiere.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">SEMESTRE</label>
                                        <input type="text" class="form-control" disabled v-model="emploi.semestre">
                                    </div>
                                    <div class="form-group">
                                        <label for="">MATIERE</label>
                                        <input type="text" class="form-control form-control-lg" disabled v-model="emploi.matiere.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">ENSEIGNANT</label>
                                        <input type="text" class="form-control form-control-lg" disabled v-model="emploi.enseignant.name">
                                    </div>
                                    <div class="form-group">
                                        <label for="">TAUX HORAIRE</label>
                                        <input type="text" disabled class="form-control" placeholder="Cout horaire de la prestation" v-model="emploi.pu" />
                                    </div>
                                    <div class="form-group">
                                        <label for="">SALLE</label>
                                        <input type="text" class="form-control form-control-lg" disabled v-model="emploi.salle.name">
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="badge bg-primary p-2">{{ emploi.day }}</div>
                                    <div class="badge bg-primary p-2">{{ emploi.start }}</div>
                                    <div  class="badge bg-primary p-2">{{ emploi.end }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div style="min-width: 500px;" class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-0">Historique des seances</h4>
                            <button @click="toggle" class="btn btn-primary btn-sm"><i class="pli-check-2"></i> Effectuer un pointage</button>
                        </div>
                    </div>
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
                <div>
                    <Dialog v-model:visible="visible" position="topright" modal header="Edit Profile" :style="{ width: '600px' }">
                        <template #header>
                            <div class="">
                               <h4 class="mb-0">Nouveau de pointage</h4>
                            </div>
                        </template>
                        <div>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>NOM</th>
                                        <th>AGE</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in inscriptions" :key="item.id">
                                        <td>
                                            <div>
                                                <Image pt:image:class="img-circle rounded-circle" :src="src(item.etudiant)" alt="Image" width="50" preview />
                                            </div>
                                        </td>
                                        <td><span>{{ item.etudiant.name }}</span></td>
                                        <td>{{ item.etudiant.age }}ans</td>
                                        <td><input type="checkbox" v-model="item.present" @change="mark"  id=""></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between gap-2">
                                <div>
                                    <label style="font-size: 13px;" for="">Date de seance</label>
                                    <input type="date" v-model="dt" class="form-control w-100 p-2" id="">
                               </div>
                                <div>
                                    <textarea class="form-control w-100" id="" v-model="pv" cols="30" placeholder="Quelques notes sur le contenu de la seance ..." rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                        <template #footer>
                            <button  text class="btn btn-primary btn-sm" @click="submit"><i class="pli-data-yes"></i> Enregsitrer</button>
                            <button  class="btn btn-danger btn" @click="visible = false">Annuler</button>
                        </template>
                    </Dialog>
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
import Dialog from 'primevue/dialog';
import Image from 'primevue/image';

import avatar from '~/img/avatar.png';

import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-balham.css"; // Optional Theme applied to the grid
import { AgGridVue } from "ag-grid-vue3";

export default {
    name:"EmploisShow",
    props: ['tkn'],
    components:{
        Display,
        PageHeader,
        BreadCrumb,
        AgGridVue,
        Dialog,
        Image,
    },
    data(){
        return {

            toaster: createToaster({ position:'top-right'}),
            visible:false,
            avatar: avatar,
            dt:null,
            pv:null,
            emploi:{
                filiere:{},
                enseignant:{},
                matiere:{},
                salle:{},
            },
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            pointages:[],
            inscriptions:[],
            cols:[
                { field: "id",filter:false,hide:true },
                { ield: "token",filter:false,hide:true },
                {field:'created',headerName:'Date '},
                {field:'nb',headerName:'Absents'},

            ],
            pagination:true,
            paginationPageSize:20,
            paginationPageSizeSelector:[20, 50, 100],
        }
    },
    computed:{
        rowData(){
            return this.pointages.map(function(item){
                return {
                    id:item.id,
                    nb:item.nb,
                    created:item.created,
                    token:item.token,
                }
            })
        },

        seance(){
            return {
                dt:this.dt,
                absents:this.inscriptions.filter((item=>{
                    return !item.present;
                })).map((ins)=>{
                    return ins.id;
                }),
                emploi_id:this.emploi.id,
                pv:this.pv,
            }
        },
        title(){
            if(this.emploi.day!=undefined){
                return `${this.emploi.filiere.code}${this.emploi.niveau_id}  ${this.emploi.day}: ${this.emploi.start}-${this.emploi.end}`;
            }else{
                return ""
            }
        },
    },
    methods:{
        src(item){
            if(item.photo!=null){
                return item.photo;
            }
            return this.avatar;
        },
        mark(event){
            console.log(event.target.checked);
            console.log(this.inscriptions)
        },
        async submit(){
            console.log(this.seance);
            this.visible = false;
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await axios.post('/api/emploi/pointage',this.seance)
                        .then((res)=>{
                            console.log(res.data);
                            this.load();
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());

        },
        toggle(){
            this.visible = true;
        },
        onGridReady(params) {
            this.gridApi = params.api;
        },
        onRowSelected(event) {
            const rows = this.gridApi.getSelectedRows();
                console.log(rows[0]);
            //let  id = rows[0].id
            //this.$router.push({name:'emplois_show',params:{tkn:id}})
        },
        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            //console.log(this.tkn);
            await axios.get('/api/emplois/'+this.tkn)
                        .then((res)=>{
                            this.pointages = res.data.pointages;
                            this.inscriptions = res.data.inscriptions;
                            this.emploi = res.data.emploi;

                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
    },
    mounted(){
        this.load().then(()=>{
            this.inscriptions.map((inscription)=>{
                inscription.present=false;
                return inscription;
            })

            console.log(this.inscriptions);
        });

    }

}
</script>

<style scoped>
    .form-control{
        padding: .2rem 1rem;
        width: 80px;
        font-size: 0.75rem;
    }
    .form-control-lg{
        width: 200px;
    }
   table.table-sm td{
        font-size: 13px;
        vertical-align: middle;
    }
</style>











