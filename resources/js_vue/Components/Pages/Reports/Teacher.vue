<template>
    <Display  :sb="true">
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'RAPPORTS',path:'/ecolages'}" :link_2="'RAPPORT PROFESSEUR'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="'Etat d\'activite du professeur'" :h1="title" ></PageHeader>
            <form class="searchbox searchbox--auto-expand searchbox--hide-btn input-group">
                <AutoComplete placeholder="Nom de l'enseignant" pt:input:class="searchbox__input form-control bg-transparent" pt:input:style="min-width:300px; height:fit-content" v-model="enseignant" optionLabel="name" :suggestions="filteredItems" @change="selectEns" @complete="search">
                    <template #option="slotProps">
                        <div class="fs-6 d-flex gap-1">
                            <img class="rounded-circle" :src="slotProps.option.photo" style="width: 25px;" alt="">
                            <div>{{ slotProps.option.name }}</div>
                        </div>
                    </template>
                </AutoComplete>
                <div class="searchbox__backdrop">
                    <button class="searchbox__btn header__btn btn btn-icon rounded shadow-none border-0 btn-sm" type="button" id="button-addon2">
                        <i class="demo-pli-magnifi-glass"></i>
                    </button>
                </div>
            </form>
      </template>
      <template v-slot:content>
        <div class="d-flex gap-2 mt-1">
            <div class="card" style="min-width:420px">
                <div class="card-body">
                    <div v-if="enseignant.diplome!=undefined" class="d-flex gap-2 mb-2">
                        <div>
                            <img :src="enseignant.photo" class="img-thumbnail" alt="" style="width: 220px;height: 100px;">
                        </div>
                        <div class="bg-light w-100 p-2">
                            <h3>{{ enseignant.name }}</h3>
                            <h5>{{ enseignant.age }} ans</h5>
                            <h5>Niveau :  {{ enseignant.diplome }}</h5>
                        </div>
                    </div>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr class="">
                                <th class="">MOIS</th>
                                <th class="">HEURES</th>
                                <th class="">HONORAIRES</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in items" :key="item.mois">
                                <td>{{ item.mois }}</td>
                                <td><span class="float-right">{{ item.heures }}</span></td>
                                <td><span class="float-right text-danger">{{ item.honoraires }}</span></td>
                            </tr>
                            <tr class="table-success">
                                <th>TOTAL</th>
                                <th><span class="float-right">{{ theures }}</span></th>
                                <th><span class="float-right">{{ thonoraires }}</span></th>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="w-100">
                <div class="d-flex gap-1">
                <div class="card w-50">
                    <div class="card-body" style="max-height: 300px;">
                        <Pie
                            v-if="loaded"
                            id="heures-chart-id"
                            :options="chartOptions"
                            :data="chartData"
                        />
                    </div>
                </div>
                <div class="card w-50">
                    <div class="card-body" style="max-height: 300px;">
                        <Pie v-if="loaded" :data="pieData" :options="chartOptions" />
                    </div>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>SEMESTRE</th>
                                <th>FILIERE</th>
                                <th>NIVEAU</th>
                                <th>MATIERE</th>
                                <th>JOUR</th>
                                <th>DEBUT</th>
                                <th>FIN</th>
                                <th>SEANCES</th>
                                <th>HEURES</th>
                                <th>HONO.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in emplois" :key="item.id">
                                <td>{{ item.semestre }}</td>
                                <td>{{ item.filiere.code}}</td>
                                <td>NIVEAU {{ item.niveau_id }}</td>
                                <td>{{ item.matiere.name }}</td>
                                <td>{{ item.day }}</td>
                                <td>{{ item.start }}</td>
                                <td>{{ item.end }}</td>
                                <td>{{ item.ts }}</td>
                                <td>{{ item.thr }}</td>
                                <td>{{ item.thn }}</td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
            </div>
        </div>
     </template>
     <template v-slot:sidebar>
        <div>
            <div class="">
                <h6 class="text-bg-primary fs-4 text-center">TimeLine des activites</h6>
                <hr>
            </div>
            <div class="timeline">
                <div v-for="p in pointages" :key="p.id" class="tl-entry">
                    <div class="tl-time">
                        <div class="tl-date">{{ p.created }}</div>
                        <div class="tl-time">{{ p.start }}-{{ p.end }}</div>
                    </div>
                    <div class="tl-point"></div>
                    <div class="tl-content card">
                        <div class="card-body p-2">
                            <h6 style="font-weight: 900;" class="text-primary fs-5">{{ p.matiere.code }}</h6>
                            <span class="badge bg-danger">{{ p.filiere.code }}{{ p.niveau_id }}</span>
                            <figure class="m-0">
                                <p class="quote fs-6">{{ p.pv }}</p>
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </template>
    </Display>
</template>
<script>

  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement,ArcElement, CategoryScale, LinearScale } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement,ArcElement, CategoryScale, LinearScale)
  export default {

    name: "ReportStudent",
    data() {
      return {
        enseignant:{},
        theures:0,
        thonoraires:0,
        pointages:[],
        items:[],
        mois:[],
        filteredItems:[],
        enseignants:[],
        emplois:[],
        loaded:false,
        chartData: {
            labels: [],
            datasets: [
                {
                    label: 'Repartion des honoraires par filiere',
                    data: [],
                    backgroundColor:['#1062b8','#E456A7']
                },
             ]
        },
        chartOptions: {
            responsive: true,
            maintainAspectRatio: false
        },
        pieData:{
            labels: [],
            datasets: [
                {
               // label: 'Repartion des honoraires par cours',
                backgroundColor: ['#1062b8', '#E46651', '#00D8FF', '#DD1B16'],
                data: [40, 20, 80, 10]
                },

            ]
        }
      };
    },

    computed:{
        title(){
            if(this.enseignant.diplome!=undefined){
                    return `${this.enseignant.name} - ${this.enseignant.diplome}`;
                }
                return 'ETAT DU PROFESSEUR';
        }
    },

    mounted(){
      this.load();
      this.initJs();
      this.emitter.emit('sidebar');
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
      async selectEns(event){
            console.log(event.value);
            let item = event.value;
            if(this.enseignant.id!=undefined){
                this.loaded = false;
                let loader = this.$loading.show({
                        container: this.fullPage ? null : this.$refs.formContainer,
                        canCancel: false,
                    });
            await this.api.get(`/api/reports/enseignant/${this.enseignant.id}`)
                        .then((res)=>{
                            console.log(res.data);
                            this.items = res.data.groups;
                            this.somme = res.data.somme;
                            this.reste = res.data.reste;
                            this.chartData.labels = res.data.data.filieres.labels;
                            this.chartData.datasets[0].data = res.data.data.filieres.data;
                            this.pieData.labels = res.data.data.matieres.labels;
                            this.pieData.datasets[0].data = res.data.data.matieres.data;

                            this.emplois = res.data.emplois;
                            //console.log(this.chartData);
                            this.pointages = res.data.pointages;
                            this.loaded = true;
                        })
                        .catch(()=>{
                            this.toaster.error("Erreur au chargement des donnees !!!");
                        })
                        .finally(()=>loader.hide())
            }

        },
        search(event){
            {
                setTimeout(() => {
                    if (!event.query.trim().length) {
                        this.filteredItems = [...this.enseignants];
                    } else {
                        this.filteredItems = this.enseignants.filter((item) => {
                            return item.name.toLowerCase().startsWith(event.query.toLowerCase());
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

            await this.api.get('/api/reports/init')
                        .then((res)=>{
                            this.enseignants = res.data.enseignants;
                            this.filteredItems = this.enseignants;

                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec lors du chargement des informations");
                        })
                        .finally(()=>loader.hide());
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
    .table-sm th,.table-sm td{
        font-size: 12px;
    }
</style>
