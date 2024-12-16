<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES FRAIS DE SCOLARITE',path:'/ecolages'}" :link_2="'FRAIS DE SCOLARITE'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="title" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="">
                <div class="">
                    <div>
                        <div class="d-flex gap-2">
                            <div class="card">
                                <div class="card-body">
                                    <fieldset class="mb-2 p-1">
                                        <legend style="font-size: 12px;">Paiement</legend>
                                            <form @submit.prevent="toggle">
                                                <div class="d-flex mb-2 gap-2">
                                                    <div style="align-content: center;">
                                                        <AutoComplete placeholder="Nom de l'etudiant" pt:input:class="form-control" pt:input:style="min-width:200px; height:fit-content" v-model="inscription" optionLabel="name" :suggestions="filteredItems" @change="selectIns" @complete="search">
                                                            <template #option="slotProps">
                                                                <div class="fs-6 d-flex gap-1">
                                                                    <img class="rounded-circle" :src="slotProps.option.photo" style="width: 25px;" alt="">
                                                                    <div>{{ slotProps.option.name }} - {{ slotProps.option.matricule}}</div>
                                                                </div>
                                                            </template>
                                                        </AutoComplete>
                                                    </div>
                                                    <div class="form-group" style="align-content: center;">
                                                        <select @change="selectMois" required class="form-control">
                                                            <option value="0">Veuillez selectionner le mois ...</option>
                                                            <option v-for="ens in mois" :key="ens.id" :value="ens.id">{{ ens.name }}</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="number" placeholder="montant" v-model="ecolage.montant" class="form-control">
                                                    </div>

                                                    <div class="form-group">
                                                        <button class="btn btn-danger btn-sm"><i class="pli-data-yes"> Enregistrer</i></button>
                                                    </div>

                                                </div>
                                            </form>
                                    </fieldset>
                                    <div class="d-flex gap-3 flex-wrap justify-content-between infos">
                                        <div>
                                            <img :src="student.photo" class="rounded-circle" style="width: 80px;" alt="">
                                        </div>
                                        <div class="form-group">
                                            <label for="">CLASSE</label>
                                            <input disabled type="text" v-model="infos.classe" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">MONTANT A PAYER</label>
                                            <input disabled type="text" v-model="tarif" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">MATRICULE</label>
                                            <input type="text" disabled v-model="student.matricule" placeholder="MATRICULE" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">NOM</label>
                                            <input type="text" disabled v-model="student.last_name" placeholder="NOM de l'etudiant" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">PRENOM</label>
                                            <input disabled type="text" v-model="student.first_name" placeholder="NOM de l'etudiant" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">TUTEUR</label>
                                            <input disabled type="text" v-model="student.tuteur.name"  class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">DATE DE NAISSANCE</label>
                                            <input disabled type="date" v-model="student.dtn"  class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">TELEPHONE</label>
                                            <input disabled type="text" v-model="student.phone" class="rounded-2">
                                        </div>
                                        <div class="form-group">
                                            <label for="">NATIONALITE</label>
                                            <input disabled type="email" v-model="student.pays" class="rounded-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card" style="min-width: 400px;">
                                <div class="card-body">
                                    <ag-grid-vue
                                    :rowData="rowData"
                                    :columnDefs="cols"
                                    style="height: 200px"
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
                    </div>

                </div>
            </div>
            <div>
                    <Dialog v-model:visible="visible" position="top" modal header="Edit Profile" :style="{ width: '300px' }">
                        <template #header>
                            <div class="">
                               <h4 class="mb-0">Avertissement</h4>
                            </div>
                        </template>
                        <div>
                            <h6 class="mb-0">VALIDATION D'UN MONTANT DE </h6>
                            <span style="color: red; font-size: 20px; margin-left: 20px; font-weight: bold; font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;" class="text-danger">{{ ecolage.montant }}</span>
                            <p style="font-size: 12px;">Attention! Cette operation est irreversible</p>
                        </div>
                        <template #footer>
                            <button  text class="btn btn-danger btn-sm" @click="submit"><i class="pli-data-yes"></i> Valider</button>
                            <button  class="btn btn-light btn" @click="visible = false">Annuler</button>
                        </template>
                    </Dialog>
                </div>

        </template>
    </Display>

</template>

<script>
import { createToaster } from "@meforma/vue-toaster";
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';

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
        AutoComplete,
        Dialog,

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

            visible:false,

            mois:[],
			inscriptions:[],
            filteredItems:[],
            etudiants:[],
            ecolages:[],
            tarifs:[],
            tarif:0,
            ecolage:{},
            inscription:{},
            cols:[
                {field:"id",filter:false,hide:true },
                {field:"token",filter:false,hide:true},
                {field:'mois',headerName:'Mois',flex:1},
                {field:'montant',headerName:'Montant'},
                {field:'created',headerName:'Jour',flex:1},
            ],
            pagination:true,
            paginationPageSize:5,
            paginationPageSizeSelector:[5, 10, 50],
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
                    token:item.token,
                }
            })
        },
        student(){
            if(this.inscription.inscription!=undefined){
                return this.inscription.inscription.etudiant;
            }else{
                return {
                    tuteur:{}
                };
            }
        },
        infos(){
            if(this.inscription.inscription!=undefined){
                return {
                    classe:`${this.inscription.inscription.filiere.code}${this.inscription.inscription.niveau_id}`,
                };
            }else{
                return {

                };
            }
        },
        title(){
            if(this.inscription.inscription!=undefined){
                return `${this.inscription.inscription.etudiant.name} - ${this.inscription.inscription.filiere.code}${this.inscription.inscription.niveau_id}`;
            }else{
                return 'PAIEMENT DES FRAIS DE SCOLARITE';
            }
        }
    },
    methods:{
        toggle(){
            if(this.ecolage.montant>0 && this.ecolage.mois_id>0){
                this.visible = true;
            }

        },
        onGridReady(params) {
            this.gridApi = params.api;
        },
        onRowSelected(event) {
            console.log(event);
        },
        async selectIns(event){
            console.log(event.value.inscription);
            let item = event.value.inscription;
            if(item!=undefined){
                let loader = this.$loading.show({
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: false,
                    });
            await this.api.get(`/api/inscription/ecolages/${item.id}`)
                        .then((res)=>{
                            this.ecolages = res.data.ecolages;
                            this.tarif = res.data.montant;
                        })
                        .catch(()=>{
                            this.toaster.error("Erreur au chargement des donnees !!!");
                        })
                        .finally(()=>loader.hide())
            }

        },
        selectMois(event){
            this.ecolage.mois_id = event.target.value;
            console.log(this.inscription);
        },
    async load(){
        await this.api.get('/api/ecolages/create')
            .then((res)=>{
                console.log(res.data);
                this.mois = res.data.mois;
                this.inscriptions = res.data.inscriptions;
                this.tarifs = res.data.tarifs;
                this.etudiants = this.inscriptions.map((item)=>{
                    return {
                        name:item.etudiant.name,
                        matricule:item.etudiant.matricule,
                        photo:item.etudiant.photo,
                        inscription:item,
                    }
                });
                this.filteredItems = this.etudiants;
                console.log(this.etudiants);
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
            })
      },
      async submit(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
                this.visible = false;
            this.ecolage.inscription_id = this.inscription.inscription.id;
            await this.api.post('/api/ecolages',this.ecolage)
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
      search(event){
            {
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredItems = [...this.etudiants];
                    } else {
                        this.filteredItems = this.etudiants.filter((item) => {
                            return item.name.toLowerCase().startsWith(event.query.toLowerCase())||item.matricule.toLowerCase().startsWith(event.query.toLowerCase());
                        });
                    }
                }, 250);
            }
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
