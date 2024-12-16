<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'RAPPORTS',path:'/ecolages'}" :link_2="'RAPPORT DES PAIEMENTS ETUDIANT'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="'Etat des paiements de l\'etudiant'" :h1="title" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-4">
                    <AutoComplete placeholder="Nom de l'etudiant" pt:input:class="form-control" pt:input:style="min-width:300px; height:fit-content" v-model="inscription" optionLabel="name" :suggestions="filteredItems" @change="selectIns" @complete="search">
                        <template #option="slotProps">
                            <div class="fs-6 d-flex gap-1">
                                <img class="rounded-circle" :src="slotProps.option.photo" style="width: 25px;" alt="">
                                <div>{{ slotProps.option.name }} - {{ slotProps.option.matricule}}</div>
                            </div>
                        </template>
                    </AutoComplete>
                    <div>
                        <select @change="selectItem" v-model="filiere_id" class="form-control">
                            <option value="0">CHOISIR LA FILIERE ...</option>
                            <option v-for="fil in filieres" :key="fil.id" :value="fil.id">{{ fil.name }}</option>
                        </select>
                    </div>
                    <div>
                        <select @change="selectItem" v-model="niveau_id" class="form-control">
                            <option value="0">CHOISIR LE NIVEAU ...</option>
                            <option v-for="i in [1,2,3]" :key="i" :value="i">NIVEAU {{ i }}</option>
                        </select>
                    </div>
                    <div>
                        <select @change="SelectInscription" class="form-control">
                            <option value="0">SELECTIONNER L'ETUDIANT ...</option>
                            <option v-for="ins in filteredIns" :key="ins.id" :value="ins.id">{{ ins.etudiant.name }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-1">
            <div class="card" style="min-width:420px">
                <div class="card-header">
                    <div>
                        <button @click="toggle" class="btn btn-xs btn-danger"><i class="pli-printer"></i> Imprimer</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <caption>Historique de tous les paiements faits par l'etudiant pendant l'annee en cours.</caption>
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-white">MOIS</th>
                                <th class="text-white">PAIEMENTS</th>
                                <th class="text-white">RESTE</th>
                                <th class="text-white">NB</th>
                                <th class="text-white">ABSENCES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.mois">
                                <td>{{ item.mois }}</td>
                                <td><span class="float-right">{{ item.total }}</span></td>
                                <td><span class="float-right text-danger">{{ item.reste }}</span></td>
                                <td><span class="float-right">{{ item.count }}</span></td>
                                <td><span class="float-right">{{ item.absences }}</span></td>
                            </tr>
                            <tr class="table-success">
                                <th>TOTAL</th>
                                <th><span class="float-right">{{ somme }}</span></th>
                                <th><span class="float-right">{{ reste }}</span></th>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card w-100">
                <div class="card-body">
                    <div style="max-height: 300px;">
                        <Bar
                            v-if="loaded"
                            id="ecolages-chart-id"
                            :options="chartOptions"
                            :data="chartData"
                        />
                    </div>
                    <hr>
                    <div style="max-height: 300px;">
                        <Bar
                            v-if="loaded"
                            id="abs-chart-id"
                            :options="chartOptions"
                            :data="absChartData"
                        />
                    </div>
                </div>
            </div>
            <div>
                    <Dialog v-model:visible="visible" position="topright" modal header="Apercu" :style="{ width: '600px' }">
                        <template #header>
                            <div class="">
                               <h4 class="mb-0">Apercu</h4>
                            </div>
                        </template>
                        <div  ref="document">
                            <div id="element-to-convert" v-if="loaded">

                                <div class="d-flex gap-2 mb-2">
                                    <div>
                                        <img :src="inscrit.etudiant.photo" class="img-thumbnail" alt="">
                                    </div>
                                    <div class="bg-light w-100 p-2">
                                        <h3>{{ inscrit.etudiant.name }}</h3>
                                        <h5>MATRICULE : {{ inscrit.etudiant.matricule }}</h5>
                                        <h5>{{ inscrit.etudiant.age }} ans</h5>
                                        <h5>{{ inscrit.filiere.name }} - NIVEAU {{ inscrit.niveau_id }}</h5>
                                    </div>
                                </div>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr class="">
                                            <th class="">MOIS</th>
                                            <th class="">PAIEMENTS</th>
                                            <th class="">RESTE</th>
                                            <th class="text-sm">ABSENCES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in items" :key="item.mois">
                                            <td>{{ item.mois }}</td>
                                            <td><span class="float-right">{{ item.total }}</span></td>
                                            <td><span class="float-right text-danger">{{ item.reste }}</span></td>
                                            <td><span class="float-right">{{ item.absences }}</span></td>
                                        </tr>
                                        <tr class="table-success">
                                            <th>TOTAL</th>
                                            <th><span class="float-right">{{ somme }}</span></th>
                                            <th><span class="float-right">{{ reste }}</span></th>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>
                        <template #footer>
                            <button  text class="btn btn-primary btn-sm" @click="exportToPDF"><i class="pli-data-yes"></i> Imprimer</button>
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
  import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  import Display from '@/Components/Layout/Display.vue';
  import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
  import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
  import AutoComplete from 'primevue/autocomplete';
  import Dialog from 'primevue/dialog';

  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  export default {
    components: {
      Display,
      PageHeader,
      BreadCrumb,
      AutoComplete,
      Bar,
      Dialog,
    },
    name: "ReportStudent",
    data() {
      return {
        title:'Nouveau Tuteur',
        description:'Formulaire de creation d\'un nouveau tuteur',
        toaster: createToaster({ position:'top-right'}),
        editor: ClassicEditor,
        editorConfig: this.editorConfig,
        visible:false,
        inscriptions:[],
        inscription:{},
        selected:{},
        niveau_id:0,
        filiere_id:0,
        somme:0,
        reste:0,
        items:[],
        filieres:[],
        mois:[],
        //months:[],
        filteredItems:[],
        filteredIns:[],
        etudiants:[],
        loaded:false,
        chartData: {
            labels: [ 'Janvier', 'February', 'March' ],
            datasets: [
                {   label: 'paiements',
                    data: [],
                    backgroundColor:'#1062b8'
                },
                {   label: 'Reste',
                    data: [],
                    backgroundColor:'#dc4030'
                }
             ]
        },
        absChartData: {
            labels: [],
            datasets: [
                {   label: 'heures d\'absence',
                    data: [],
                    backgroundColor:'#dc4030'
                },
             ]
        },
        chartOptions: {
            responsive: true
        },
      };
    },

    computed:{
        inscrit(){
            if(this.inscription.inscription!=undefined){
                return this.inscription.inscription;
            }else{
                if(this.selected.etudiant!=undefined){
                    return this.selected;
                }

            }
        },
        title(){
            if(this.inscription.inscription!=undefined){
                return `${this.inscription.inscription.etudiant.name} - ${this.inscription.inscription.filiere.code}${this.inscription.inscription.niveau_id}`;
            }else{
                if(this.selected.etudiant!=undefined){
                    return `${this.selected.etudiant.name} - ${this.selected.filiere.code}${this.selected.niveau_id}`;
                }
                return 'PAIEMENT DES FRAIS DE SCOLARITE';
            }
        }
    },

    mounted(){
      this.load();
      this.initJs();
    },
    methods: {
      initJs(){
        const myScript = document.createElement("script");
        myScript.setAttribute(
        "src",
        "/js/html2pdf.bundle.min.js"
        );
        document.head.appendChild(myScript);
      },
      selectItem(event){
        this.filteredIns = this.inscriptions.filter((inscription)=>{
            return inscription.filiere_id == this.filiere_id && inscription.niveau_id == this.niveau_id
        })
        console.log(this.filteredIns);
      },
      toggle(){
            this.visible = true;
        },
      async SelectInscription(event){
            console.log(event.target.value);
            let id = event.target.value;

            if(id!=undefined){
                this.loaded = false;
                this.inscription = {};
                this.selected = this.inscriptions.filter((item)=>item.id==id)[0];
                console.log(this.selected.etudiant);
                let loader = this.$loading.show({
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: false,
                    });
                await this.api.get(`/api/reports/inscription/${id}`)
                        .then((res)=>{
                            console.log(res.data);
                            this.items = res.data.groups;
                            this.somme = res.data.somme;
                            this.reste = res.data.reste;
                            this.chartData.labels = res.data.labels;

                            this.chartData.datasets[0].data = res.data.v;
                            this.chartData.datasets[1].data = res.data.r;
                            //absences
                            this.absChartData.datasets[0].data = res.data.absences;
                            this.absChartData.labels = res.data.labels;
                            //console.log(this.chartData);
                            this.loaded = true;
                        })
                        .catch(()=>{
                            this.toaster.error("Erreur au chargement des donnees !!!");
                        })
                        .finally(()=>loader.hide())
            }

        },
      async selectIns(event){
            this.selected = {};
            console.log(event.value.inscription);
            let item = event.value.inscription;
            if(item!=undefined){
                this.loaded = false;
                let loader = this.$loading.show({
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: false,
                    });
            await this.api.get(`/api/reports/inscription/${item.id}`)
                        .then((res)=>{
                            console.log(res.data);
                            this.items = res.data.groups;
                            this.somme = res.data.somme;
                            this.reste = res.data.reste;
                            this.chartData.labels = res.data.labels;
                            this.chartData.datasets[0].data = res.data.v;
                            this.chartData.datasets[1].data = res.data.r;

                             //absences
                             this.absChartData.datasets[0].data = res.data.groups.map((grp)=>grp.absences);
                            this.absChartData.labels = res.data.labels;
                            //console.log(this.chartData);
                            this.loaded = true;
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
      async load(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });

            await this.api.get('/api/reports/create')
                        .then((res)=>{
                            this.inscriptions = res.data.inscriptions;
                            this.filieres = res.data.filieres;
                            this.mois = res.data.mois;
                            this.etudiants = this.inscriptions.map((item)=>{
                                return {
                                    name:item.etudiant.name,
                                    matricule:item.etudiant.matricule,
                                    photo:item.etudiant.photo,
                                    inscription:item,
                                }
                            });
                            this.filteredItems = this.etudiants;
                            //this.filteredIns = this.inscriptions;

                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec lors du chargement des informations");
                        })
                        .finally(()=>loader.hide());
      },
      createPDF () {
        //console.log(this.$refs.pdf);
       // this.$refs.pdf.download()
        //this.$refs.pdf.openInNewTab()
       // this.$refs.html2Pdf.generatePdf();
        },
        exportToPDF() {
            html2pdf(document.getElementById("element-to-convert"), {
                        margin: 1,
                        filename: `${this.inscrit.etudiant.matricule}.pdf`,
                        html2canvas:  { scale: 2 },
                        jsPDF:{ unit: 'mm', format: 'a4', orientation: 'portrait' }
                    });
        }
    },
  };
  </script>

<style scoped>
    .form-group{
        margin-top:20px;
    }
    .float-right{
        float: right;
    }
    .table-sm th,.table-sm td{
        font-size: 12px;
    }
</style>
