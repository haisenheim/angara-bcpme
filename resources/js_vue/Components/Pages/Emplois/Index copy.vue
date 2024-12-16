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
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <div class="form-group">
                                    <label for="">FILIERE</label>
                                    <select @change="selectFiliere" class="form-control">
                                        <option v-for="filiere in filieres" :key="filiere.id" :value="filiere.id">{{filiere.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">SEMESTRE</label>
                                    <select @change="selectSemestre" style="min-width: 240px;" class="form-control">
                                        <option v-for="n in [1,2,3,4,5,6]" :key="n" :value="n">SEMESTRE {{n}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">MATIERE</label>
                                    <select @change="selectMatiere" style="min-width: 240px;" class="form-control">
                                        <option v-for="cr in cours" :key="cr.id" :value="cr.id">{{ cr.matiere.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">ENSEIGNANT</label>
                                    <select @change="selectEnseignant" style="min-width: 240px;" class="form-control">
                                        <option v-for="ens in enseignants" :key="ens.id" :value="ens.id">{{ ens.name }}</option>
                                    </select>
                                </div>

                            </div>
                            <div class="d-flex justify-content-between gap-3">
                                <select class="form-control" v-model="day">
                                    <option v-for="day in weekDays" :key="day" :value="day">{{day}}</option>
                                </select>
                                <input type="text" class="form-control" placeholder="heure de debut" v-model="emploi.start" />
                                <input type="text" class="form-control" placeholder="heure de fin" v-model="emploi.end" />
                                <div>
                                    <button @click="submit" class="btn btn-primary"><i class="pli-add"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <div class="mb-1">
                            <input @input="search" type="text" style="max-width: 500px;" class="form-control bg-light p-2 border-white" placeholder="Rechercher ...">
                        </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>JOUR</th>
                                <th>COURS</th>
                                <th>DEBUT</th>
                                <th>FIN</th>
                                <th>DUREE</th>
                                <th>SALLE</th>
                                <th>ENSEIGNANT</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="emp in filtered" :key="emp.id">
                                <td>{{ emp.day }}</td>
                                <td>{{ emp.matiere.name }}</td>
                                <td>{{ emp.start }}</td>
                                <td>{{ emp.end }}</td>
                                <td>{{ emp.gap }}h</td>
                                <td>{{ emp.salle.code }}</td>
                                <td>{{ emp.enseignant.last_name }} {{ emp.enseignant.first_name }}</td>
                                <td>-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <ag-grid-vue
                    :rowData="rowData"
                    :columnDefs="cols"
                    style="height: 500px"
                    class="ag-theme-balham"
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

            cours:[],
			filieres: [],
            enseignants:[],
            filiere_id:0,
            semestre:0,
            cols:[
                {field:'filiere',headerName:'Filiere',filter:true,floatingFilter:true,flex:1},
                {field:'niveau',headerName:'Niveau',filter:true,floatingFilter:true,flex:1},
                {field:'semestre',headerName:'Semestre',filter:true,floatingFilter:true,flex:1},
                {field:'day',headerName:'Jour',filter:true,floatingFilter:true,flex:2},
                {field:'matiere',headerName:'Matiere',filter:true,floatingFilter:true,flex:3},
                {field:'start',headerName:'Debut',filter:true,floatingFilter:true,flex:1},
                {field:'end',filter:true,headerName:'Fin',floatingFilter:true,flex:1},
                {field:'gap',headerName:'Duree',filter:true,floatingFilter:true,flex:1},
                {field:'salle',filter:true,floatingFilter:true,flex:1},
                {field:'enseignant',filter:true,floatingFilter:true,flex:2}
            ],
            pagination:true,
            paginationPageSize:500,
            paginationPageSizeSelector:[200, 500, 1000],
        }
    },
    computed:{
        rowData(){
            return this.emplois.map(function(item){
                return {
                    filiere:item.filiere.code,
                    niveau:item.niveau_id,
                    semestre:item.semestre,
                    day:item.day,
                    matiere:item.matiere.name,
                    start:item.start,
                    end:item.end,
                    gap:item.gap+"h",
                    salle:item.salle.code,
                    enseignant:item.enseignant.last_name+" "+item.enseignant.first_name,
                }
            })
        }
    },
    methods:{
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
      async load(){
        await this.api.get('/api/emplois/create')
            .then((res)=>{
                console.log(res.data);
                this.filieres = res.data.filieres;
                this.enseignants = res.data.enseignants;
                this.emplois = res.data.emplois;
                this.filtered = res.data.emplois;
                this.emploi.enseignant_id = this.enseignants[0].id;
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
        search(event){
            {
                setTimeout(() => {
                    if (!event.target.value.trim().length) {
                        this.filtered = [...this.emplois];
                    } else {
                        this.filtered = this.emplois.filter((item) => {

                            return item.enseignant.last_name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.matiere.name.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.salle.code.toLowerCase().startsWith(event.target.value.toLowerCase())
                            || item.day.toLowerCase().startsWith(event.target.value.toLowerCase())

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

</style>
