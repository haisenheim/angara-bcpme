<template>
    <Display>
      <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'RAPPORTS',path:'/ecolages'}" :link_2="'RAPPORT MENSUEL'"></BreadCrumb>
      </template>
      <template v-slot:page-header>
          <PageHeader  :p="'Etat des transactions mensuelles'" :h1="title" ></PageHeader>
      </template>
      <template v-slot:content>
        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-4">
                    <div>
                        <select @change="selectMois" v-model="mois_id" class="form-control">
                            <option value="0">CHOISIR LE MOIS ...</option>
                            <option v-for="m in mois" :key="m.id" :value="m.id">{{ m.name }}</option>
                        </select>
                    </div>
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
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-1">
            <div class="card" style="min-width:420px">
                <div class="card-header">
                    <div>
                        <button class="btn btn-xs btn-danger"><i class="pli-printer"></i> Imprimer</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <caption>Historique de tous des transactions .</caption>
                        <thead>
                            <tr class="bg-primary">
                                <th class="text-white">FILIERE</th>
                                <th class="text-white">PAIEMENTS</th>
                                <th class="text-white">RESTE</th>
                                <th class="text-white">ABSENCES</th>
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

            </div>
        </div>

     </template>
    </Display>
</template>
<script>
  import { createToaster } from "@meforma/vue-toaster";

  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'

  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  export default {
    components: {
    },
    name: "ReportMonth",
    data() {
      return {
        toaster: createToaster({ position:'top-right'}),
        niveau_id:0,
        filiere_id:0,
        mois_id:0,
        somme:0,
        reste:0,
        filieres:[],
        mois:[],
        loaded:false,
        chartData: {
            labels: [],
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
        title(){
            return 'RAPPORT MENSUEL DES TRANSACTIONS';
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
      async selectMois(event){
            let id = event.target.value;
            this.loaded = false;
            await this.api.get(`/api/reports/month/${id}`)
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
            },
      async load(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });

            await this.api.get('/api/reports/init')
                        .then((res)=>{
                            this.filieres = res.data.filieres;
                            this.mois = res.data.mois;

                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec lors du chargement des informations");
                        })
                        .finally(()=>loader.hide());
      }
    }
}

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
