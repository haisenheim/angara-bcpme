<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'Accueil',path:'/dashboard'}" :link_2="'Tableau de bord'"></BreadCrumb>
        </template>

        <template v-slot:content>
            <div class="d-flex gap-2">
                <div class="card w-50">
                    <div class="card-header bg-blue-200">
                        <h5 class="card-title">MON EMPLOI DU TEMPS</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm table-striped">
                            <thead>
                                <th>JOUR</th>
                                <th>DEBUT</th>
                                <th>FIN</th>
                                <th>COURS</th>
                                <th>TAUX</th>
                            </thead>
                            <tbody>
                                <tr v-for="item in emplois" :key="item.id">
                                    <td>{{ item.day }}</td>
                                    <td>{{ item.start }}</td>
                                    <td>{{ item.end }}</td>
                                    <td>{{ item.matiere.name }}</td>
                                    <td>{{ item.pu }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card w-50" style="">
                    <div class="card-body">
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
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-blue-200">
                        <h5 class="card-title">MON TABLEAU DES HONORAIRES</h5>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <div>
                    <div class="card w-100">
                    <div class="card-body">
                        <h4>LISTE DES EVALUATIONS</h4>
                        <ag-grid-vue
                        :rowData="exRowData"
                        :columnDefs="exCols"
                        style="height: 300px"
                        class="ag-theme-balham"
                        :rowSelection="'single'"
                        :defaultColDef="defaultColDef"
                        @selection-changed="exOnRowSelected"
                        @grid-ready="exOnGridReady"
                        :pagination=true
                        :paginationPageSize=20
                        :paginationPageSizeSelector="paginationPageSizeSelector"
                        >
                    </ag-grid-vue>
                    </div>
                </div>
                </div>
            </div>
        </template>
        <template v-slot:sidebar>
            <div>
                <div class="bg-blue-200 p-1 rounded-2 mb-3">
                    <h6 class="text-bg-primary fs-4 text-center">Historique des seances de cours</h6>
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
export default {
    name:"TeacherDashboard",

    components:{

    },
    computed:{
        exRowData(){
            return this.examens.map(function(item){
                return {
                    id:item.id,
                    token:item.token,
                    filiere:item.filiere.code,
                    semestre:item.semestre,
                    matiere:item.cours.matiere.code,
                    mois:item.mois.name,
                    evaluation:item.evaluation.name,
                    activite:item.type.name,
                    periode:item.periodicite.name,
                    pourcentage:`${item.pourcentage}%`,
                }

            });
        }
    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Bienvenu dans SKULU, la plateforme de gestion scolaire!',
            examens:[],
            emplois:[],
            items:[],
            pointages:[],
            exGridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            paginationPageSizeSelector:[20, 50, 100],
            exCols:[
                { field: "id",filter:false,hide:true },
                { field: "token",filter:false,hide:true },
                {field:'filiere',headerName:'Filiere',flex:1},
                {field:'semestre',headerName:'Semestre'},
                {field:'mois',headerName:'Mois'},
                {field:'matiere',headerName:'Matiere',flex:1},
                {field:'evaluation',headerName:'Intitule',flex:3},
                {field:'activite',headerName:'Activite',flex:2},
                {field:'periode',headerName:'Frequence',flex:1},
                {field:'pourcentage',headerName:'%',flex:1},

            ],
        }
    },
    methods:{
        exOnGridReady(params) {
            this.exGridApi = params.api;
        },
        exOnRowSelected(event) {
                const rows = this.gridApi.getSelectedRows();
                console.log(rows[0]);
                let  token = rows[0].token;
                this.$router.push(`/prof/examens/show/${token}`)
        },
        async load(){
            await this.api.get('/api/prof/dashboard')
            .then((res)=>{
                this.examens = res.data.examens;
                this.emplois = res.data.emplois;
                this.items = res.data.honoraires;
                this.pointages = res.data.pointages;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
            })

        }
    },
    mounted(){
        this.emitter.emit('sidebar');
        this.load();
    }

}
</script>

<style scoped>

</style>
