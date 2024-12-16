<template>
    <main class="main">
        <!-- ======= Breadcrumbs ======= -->
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                <ol>
                <li><a href="/">ACCUEIL</a></li>
                <li>MES DOSSIERS </li>
                <li>{{ dossier.type.name }} </li>
                </ol>
                <div class="d-flex justify-center gap-3">
                    <h2>{{ dossier.type.name }}</h2>
                    <div class="pt-1">DU <strong>{{ dossier.created }}</strong></div>
                </div>
            </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">
            <div class="d-flex justify-center">
                <div style="width: 500px" class="card bg-light-green-lighten-4">
                    <div class="card-body">
                        <h4>LE BESOIN</h4>
                        <div v-html="dossier.besoin"></div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>
<script>
import axios from "axios";

import { createToaster } from "@meforma/vue-toaster";
export default {
    name:"ShowDossier",
    props: ['tkn'],
    components:{
    },
    data(){
        return {

            dossier:{
                type:{},
            },
            toaster: createToaster({ position:'top-right'}),
            componentKey: 0,
        }
    },
    methods:{
        endpb(){
            this.reload();
            document.getElementById('closepb').click();
        },

        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await axios.get(this.path+'company/dossiers/'+this.tkn)
                        .then((res)=>{
                            this.dossier = res.data;
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
        async reload(){
            await axios.get(this.path+'company/dossiers/'+this.tkn)
                        .then((res)=>{
                            this.dossier = res.data;
                            this.toaster.success("Operation effectuee avec succes !!!");
                        })
                        .catch((err)=>console.error(err));
        }
    },
    mounted(){
        this.load().then(()=>console.log(this.dossier))

    }

}
</script>

<style scoped>
    .badge{
        background-color: #3d9970;
        color: #fff;
    }
    .breadcrumbs a{
        color: #3d9970;
        text-decoration: none;
    }
    .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
        color: var(--bs-nav-pills-link-active-color);
        background-color: #3d9970;
    }
    a {
        color: #3d9970;

    }
    .infos p{
        margin-bottom: 2px;
        font-size: smaller;
    }
    .table thead th{
        font-size: smaller;
    }
</style>
