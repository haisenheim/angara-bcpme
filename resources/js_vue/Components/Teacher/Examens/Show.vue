<template>
        <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'EVALUATIONS',path:'/examens'}" :link_2="'LISTE DES NOTES'"></BreadCrumb>
        </template>
        <template v-slot:actions>
            <li><a data-bs-toggle="modal" data-bs-target="#modal" class="dropdown-item" href="#"><i class="pli-add"></i> Ajouter un note</a></li>
            <li><a data-bs-toggle="modal" data-bs-target="#editmodal" class="dropdown-item" href="#"><i class="pli-pencil"></i> Editer les notes</a></li>
            <li><router-link to="/examens" class="dropdown-item"><i class="pli-list-view"></i> Liste des evaluations</router-link></li>
        </template>
        <template v-slot:page-header>
            <PageHeader class="fs-5" :p="description" :h1="title" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
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
                        :pagination=true
                        :paginationPageSize=20
                        :paginationPageSizeSelector="paginationPageSizeSelector"
                        >
                    </ag-grid-vue>
                </div>
                <div id="modal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="mb-0">AJOUTER UNE NOTE</h5>
                                <button style="float:right;" data-bs-dismiss="modal" class="btn-xs btn btn-danger"><span class=" rounded-circle text-white">x</span></button>
                            </div>
                            <div class="modal-body">
                                <div class="d-flex gap-1">
                                    <div class="form-group">
                                        <select name="" v-model="note.inscription_id" id="" class="form-control">
                                            <option value="0">ETUDIANT ...</option>
                                            <option v-for="item in inscrits" :key="item.id" :value="item.id">{{ item.etudiant.name }} - {{ item.etudiant.matricule }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" v-model="note.note" placeholder="Note" class="form-control">
                                    </div>
                                    <div class="form-group mt-1">
                                        <button @click="submit" data-bs-dismiss="modal" class="btn btn-danger btn-sm"><i class="pli-disk"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edition des notes en masse -->
                <div id="editmodal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="mb-0">Editer les notes</h5>
                                <button style="float:right;" data-bs-dismiss="modal" class="btn-xs btn btn-danger"><span class=" rounded-circle text-white">x</span></button>
                            </div>
                            <div class="modal-body">
                                <div>
                                    <table class="table table-bordered table-sm">
                                        <tbody>
                                            <tr v-for="item in donnees" :key="item.inscription_id">
                                                <td>
                                                    <div>
                                                        <img :src="item.etudiant.photo" class="rounded-circle" width="50" alt="">
                                                    </div>
                                                </td>
                                                <td>{{ item.etudiant.name }} - {{ item.etudiant.matricule }}</td>

                                                <td>
                                                    <div style="width:80px; display:table-cell; vertical-align: middle;">
                                                        <input class="form-control p-1"  type="text" v-model="item.note">
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button @click="save" data-bs-dismiss="modal" class="btn btn-primary btn-sm"><i class="pli-disk"></i> ENREGISTRER </button>
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
    name:"ShowDossier",
    props: ['tkn'],
    components:{
    },
    data(){
        return {

            description:'Lites des notes de l\'evaluation',
            examen:null,
            notes:[],
            note:{
                inscription_id:0,
                note:0,
            },
            inscrits:[],
            gridApi: null,
            defaultColDef: {
                flex: 1,
                filter:true,
                floatingFilter:true,
            },
            paginationPageSizeSelector:[20, 50, 100],
            cols:[
                {field: "id",filter:false,hide:true },
                {field: "token",filter:false,hide:true },
                {field:'etudiant',flex:2},
                {field:'matricule'},
                {field:'age'},
                {field:'note'},
            ],
        }
    },
    computed:{
        title(){
            if(this.examen!=null){
                return `${this.examen.evaluation.name} - ${this.examen.cours.matiere.name} - ${this.examen.filiere.code} - S${this.examen.semestre}`

            }else{
                return "";
            }

        },
        donnees(){
            let examen = this.examen;
            return this.inscrits.map(function(item){
                return {
                    etudiant:item.etudiant,
                    niveau_id:item.niveau_id,
                    etudiant_id:item.etudiant_id,
                    inscription_id:item.id,
                    examen_id:examen!=null?examen.id:0,
                    evaluation_id:examen!=null?examen.evaluation.id:0,
                    cours_id:examen!=null?examen.cours.id:0,
                    type_id:examen!=null?examen.type.id:0,
                    periodicite_id:examen!=null?examen.periodicite.id:0,
                    pourcentage:examen!=null?examen.pourcentage:0,
                    filiere_id:item.filiere_id,
                    semestre:examen!=null?examen.semestre:0,
                    matiere_id:examen!=null?examen.cours.matiere.id:0,
                    note:0,
                    mois_id:examen!=null?examen.mois.id:0,
                };
            });
        },
        rowData(){
            return this.notes.map(function(item){
                return {
                    id:item.id,
                    token:item.token,
                    etudiant:item.etudiant.name,
                    matricule:item.etudiant.matricule,
                    age:item.etudiant.age,
                    note:item.note,
                }

            });
        }
    },
    methods:{
        onGridReady(params) {
            this.gridApi = params.api;
            let ga = params.api;
            this.emitter.emit('ready', {'gridApi':ga});
        },
        async save(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let data = this.donnees.map(function(item){
                return delete item.etudiant;
            })

            console.log(this.donnees);
            await this.api.post('/api/prof/examen/notes',this.donnees)
                        .then((res)=>{
                            this.toaster.success("Enregistrement effectue avec succes!");
                            this.load()
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/examen/'+this.tkn)
                        .then((res)=>{
                            this.examen = res.data.examen;
                            this.notes = res.data.notes;
                            this.inscrits = res.data.inscrits;
                            //ok
                            console.log(this.donnees);
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
    },
    mounted(){
        this.load();

    }

}
</script>

