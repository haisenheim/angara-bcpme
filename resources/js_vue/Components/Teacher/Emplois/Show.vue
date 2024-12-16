<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES EMPLOIS DE TEMPS',path:'/emplois'}" :link_2="title"></BreadCrumb>
        </template>
        <template v-slot:actions>
            <li><a data-bs-toggle="modal" data-bs-target="#modal" class="dropdown-item" href="#"><i class="pli-add"></i> Effectuer un pointage</a></li>
            <li><a data-bs-toggle="modal" data-bs-target="#chapterModal" class="dropdown-item" href="#"><i class="pli-add"></i> Ajouter un chapitre</a></li>
            <li><a data-bs-toggle="modal" data-bs-target="#exerciceModal" class="dropdown-item" href="#"><i class="pli-add"></i> Ajouter un exercice</a></li>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="title" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex gap-3 justify-content-between mb-3">
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0">Filiere</legend>
                                <span class="fw-bold">{{ emploi.filiere.name }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Semestre</legend>
                                <span class="fw-bold">Smestre {{ emploi.semestre }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Matiere</legend>
                                <span class="fw-bold">{{ emploi.matiere.name }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Taux horaire</legend>
                                <span class="fw-bold">{{ emploi.pu }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Salle</legend>
                                <span class="fw-bold">{{ emploi.salle.name }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Jour</legend>
                                <span class="fw-bold">{{ emploi.day }}</span>
                            </fieldset>
                        </div>
                        <div>
                            <fieldset class="p-1">
                                <legend class="m-0 text-sm">Tranche horaire</legend>
                                <span class="fw-bold">{{ emploi.start }} - {{ emploi.end }}</span>
                            </fieldset>
                        </div>
                    </div>


                        <v-tabs
                        v-model="tab"
                        bg-color="primary"
                        >
                        <v-tab value="one">Plan de cours</v-tab>
                        <v-tab value="two">Exercices</v-tab>
                        <v-tab value="three">Supports de cours</v-tab>
                        </v-tabs>

                        <v-tabs-window v-model="tab">
                            <v-tabs-window-item value="one">
                                <div>
                                    <div class="row g-1 mt-1">
                                        <div style="border-right: 1px solid #ccc" class="col mb-3 border-right mr-3 pr-3">
                                            <h4 class="d-flex align-items-center gap-2"><span class="d-inline-block bg-info rounded-circle p-1"></span> A voir</h4>
                                            <p class="text-info mb-3">Cette colonne specifie l'ensemble des chapitre qui seront abordes dans le cadre de ce cours</p>
                                            <div class="p-2 bg-light rounded">
                                                <!-- Upcoming Tasklist -->
                                                <div v-for="task in upcoming_chapters" :key="task.id" class="card mb-2">
                                                    <div class="card-body">

                                                        <h5 class="card-title">{{ task.sequence }} - {{ task.name }}</h5>

                                                        <p>{{ task.description }}</p>
                                                        <div class="mt-4 pt-3 border-top d-flex align-items-center">
                                                            <div>
                                                                <button @click="set_inprogress(task.id)" class="btn btn-xs btn-warning">suivant <i class="pli-right-2"></i></button>
                                                            </div>
                                                            <small class="text-muted ms-auto">9:25AM</small>
                                                        </div>

                                                    </div>
                                                </div>
                                                <!-- END : Upcoming Tasklist -->



                                            </div>
                                        </div>

                                        <div style="border-right: 1px solid #ccc" class="col mb-3 mr-3 pr-3">
                                            <h4 class="d-flex align-items-center gap-2"><span class="d-inline-block bg-warning rounded-circle p-1"></span> En cours</h4>
                                            <p class="text-warning mb-3">Cette colonne indique le ou les chapitre(s) en cours</p>
                                            <div class="p-2 bg-light rounded">
                                                <!-- In Progress Tasklist -->
                                                <div v-for="task in in_progress_chapters" :key="task.id" class="card mb-2">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ task.sequence }} - {{ task.name }}</h5>
                                                    <p>{{ task.description }}</p>
                                                    <div class="mt-4 pt-3 border-top d-flex justify-content-between">
                                                            <div>
                                                                <button @click="set_completed(task.id)" class="btn btn-xs btn-success">Terminer <i class="pli-like fw-bold"></i></button>
                                                            </div>
                                                            <small class="text-muted ms-auto">depuis le {{ task.status.date }}</small>
                                                    </div>
                                                    </div>
                                                </div>
                                                <!-- END : In Progress Tasklist -->
                                            </div>
                                        </div>

                                        <div class="col mb-3">
                                            <h4 class="d-flex align-items-center gap-2"><span class="d-inline-block bg-success rounded-circle p-1"></span>Terminés</h4>
                                            <p class="text-success mb-3">Cette colonne liste tous les chapitres sont cloturés</p>
                                            <div class="p-2 bg-light rounded">
                                                <!-- Completed Tasklist -->
                                                <div v-for="task in completed_chapters" :key="task.id" class="card mb-2">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ task.sequence }} - {{ task.name }}</h5>
                                                        <p>{{ task.description }}</p>
                                                        <div class="mt-4 pt-3 border-top d-flex align-items-center">
                                                            <a href="#" class="btn btn-icon btn-sm btn-link text-head">
                                                                <i class="text-muted demo-pli-heart-2 fs-5 me-2"></i>37k
                                                            </a>
                                                            <small class="text-muted ms-auto">depuis le {{ task.status.date }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- END : Completed Tasklist -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </v-tabs-window-item>

                            <v-tabs-window-item value="two">
                                <div class="container">
                                    <div v-for="exo in exercices" :key="exo.id" class="p-4 border rounded-4 mb-2">
                                        <div class="">
                                            <h6 class="card-title">{{ exo.name }}</h6>
                                            <div class="p-4 rounded bg-light">
                                                <p>{{ exo.description }}</p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <div style="width:600px; height:300px; overflow:scroll;">
                                                <PDF  :src="exo.pdf" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </v-tabs-window-item>

                            <v-tabs-window-item value="three">
                                Supports de cours ici ...
                            </v-tabs-window-item>
                        </v-tabs-window>
                </div>
            </div>
            <div class="">
                <div id="modal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="bg-primary p-2 rounded-2">
                                    <p class="text-center">Veuillez effectuer l'appel des etudiants et cocher tous ceux qui sont presents!</p>
                                </div>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm table-striped table-hover">
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
                                                    <Image pt:image:class="img-circle rounded-circle" :src="src(item.etudiant)" alt="Image" width="40" preview />
                                                </div>
                                            </td>
                                            <td><span>{{ item.etudiant.name }}</span></td>
                                            <td>{{ item.etudiant.age }}ans</td>
                                            <td><input type="checkbox" v-model="item.present" @change="mark"  id=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div>
                                    <textarea class="form-control w-100 bg-light" id="" v-model="pv" cols="30" placeholder="Quelques notes sur le contenu de la seance ..." rows="5"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-bs-dismiss="modal" text class="btn btn-primary btn-sm" @click="submit"><i class="pli-data-yes"></i> Enregsitrer</button>
                                <button data-bs-dismiss="modal"  class="btn btn-danger btn">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="chapterModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="mt-2">
                                    <input type="text" placeholder="Titre du chapitre" v-model="chapter.name" class="form-control">
                                </div>
                                <div class="mt-5">
                                    <input type="number" placeholder="Numero de sequence" v-model="chapter.sequence" class="form-control">
                                </div>
                                <div class="mt-5">
                                    <textarea class="form-control w-100" id="" v-model="chapter.description" cols="30" placeholder="Quelques notes sur le contenu du chapitre" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-bs-dismiss="modal" text class="btn btn-primary btn-sm" @click="submit_chapter"><i class="pli-data-yes"></i> Enregsitrer</button>
                                <button data-bs-dismiss="modal"  class="btn btn-danger btn">Annuler</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="exerciceModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="mt-2">
                                    <input type="text" placeholder="Titre de l'exercice" v-model="exercice.name" class="form-control">
                                </div>
                                <div class="mt-5">
                                    <textarea class="form-control w-100" id="" v-model="exercice.description" cols="30" placeholder="Quelques notes sur le contenu de l'exerice" rows="5"></textarea>
                                </div>
                                <div class="mt-2">
                                    <label for="">Fichier pdf de l'exercice</label>
                                    <input type="file" placeholder="Titre de l'exercice" @change="changePdf" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button data-bs-dismiss="modal" text class="btn btn-primary btn-sm" @click="saveExercice"><i class="pli-data-yes"></i> Enregsitrer</button>
                                <button data-bs-dismiss="modal"  class="btn btn-danger btn">Annuler</button>
                            </div>
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
import avatar from '~/img/avatar.png';
  import VuePdfEmbed,{ useVuePdfEmbed } from 'vue-pdf-embed'
  import PDF from "pdf-vue3";
export default {
    name:"EmploisShow",
    props: ['tkn'],
    components:{
        VuePdfEmbed,
        PDF,
    },
    data(){
        return {
            avatar: avatar,
            dt:null,
            pv:null,
            tab:null,
            pdfViewer:null,
            chapter:{},
            chapters:[],
            exercice:{},
            exercices:[],
            pdf:null,
            emploi:{
                filiere:{},
                enseignant:{},
                matiere:{},
                salle:{},
            },
            pointages:[],
            inscriptions:[],
        }
    },
    computed:{
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
        upcoming_chapters(){
            return this.chapters.filter((item)=>item.status.code==-1);
        },
        in_progress_chapters(){
            return this.chapters.filter((item)=>item.status.code==0);
        },
        completed_chapters(){
            return this.chapters.filter((item)=>item.status.code==1);
        }
    },
    methods:{
        src(item){
            if(item.photo!=null){
                return item.photo;
            }
            return this.avatar;
        },
        changePdf(event){
            this.pdf = event.target.files[0];
        },

        async saveExercice(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            let form = new FormData();
            if(this.pdf!=null){
                form.append('pdf',this.pdf);
                this.exercice.emploi_id = this.emploi.id;
                form.append('exercice',JSON.stringify(this.exercice))
                await this.api.post('/api/prof/emploi/exercice',form,this.multipart)
                        .then((res)=>{
                            this.post = res.data;
                            this.toaster.success("Creation de l'exercice effectuée avec succes !!!");
                            this.load();
                        })
                        .catch((err)=>{
                          console.error(err);
                          this.toaster.error("Echec de creation de l'exercice");
                        })
                        .finally(()=>loader.hide());
            }else{
                this.toaster.error("Le champ de la photo ne peut etre vide !")
            }

        },
        async set_inprogress(id){
            console.log(id);
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
                await axios.get('/api/prof/chapter/inprogress/'+id)
                    .then((res)=>{
                        console.log(res);
                        this.load();
                    })
                    .catch((err)=>console.error(err))
                    .finally(()=>loader.hide());
        },
        async set_completed(id){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
                await axios.get('/api/prof/chapter/complete/'+id)
                    .then((res)=>{
                        console.log(res);
                        this.load();
                    })
                    .catch((err)=>console.error(err))
                    .finally(()=>loader.hide());
        },
        async submit(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await axios.post('/api/prof/emploi/pointage',this.seance)
                        .then((res)=>{
                            console.log(res.data);
                            this.load();
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());

        },
        async submit_chapter(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            this.chapter.emploi_id = this.emploi.id;
            await axios.post('/api/prof/emploi/chapter',this.chapter)
                        .then((res)=>{
                            console.log(res.data);
                            this.load();
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());

        },
        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/prof/emplois/'+this.tkn)
                        .then((res)=>{
                            this.pointages = res.data.pointages;
                            this.inscriptions = res.data.inscriptions;
                            this.emploi = res.data.emploi;
                            this.chapters = res.data.chapters;
                            this.exercices = res.data.exercices.map((item)=>{
                                item.doc = useVuePdfEmbed({source: item.pdf})
                                return item;
                            });

                            console.log(this.exercices)


                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
    },
    mounted(){
        this.emitter.emit('sidebar');
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
    legend{
        font-size: 11px;
    }
    fieldset{
        font-size: smaller;
        color:#25476a;
    }
    .form-control-lg{
        width: 200px;
    }
   table.table-sm td{
        font-size: 13px;
        vertical-align: middle;
    }
</style>











