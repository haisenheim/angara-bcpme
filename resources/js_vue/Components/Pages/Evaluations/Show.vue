<template>
    <main class="main">
        <!-- ======= Breadcrumbs ======= -->
        <section id="breadcrumbs" class="breadcrumbs">
            <div class="container">
                <ol>
                <li><a href="/">ACCUEIL</a></li>
                <li><a href="/">INSCRIPTION</a></li>
                <li>{{ inscription.etudiant.name }}</li>
                </ol>
                <div class="d-flex justify-center gap-3">
                    <h2>{{ inscription.etudiant.name }}</h2>
                </div>
            </div>
        </section><!-- End Breadcrumbs -->
        <div class="container">

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

            inscription:{},
            toaster: createToaster({ position:'top-right'}),
            componentKey: 0,
        }
    },
    methods:{
        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await axios.get(this.path+'/api/inscriptions/'+this.tkn)
                        .then((res)=>{
                            this.dossier = res.data;
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
    },
    mounted(){
        this.load().then(()=>console.log(this.dossier))

    }

}
</script>

