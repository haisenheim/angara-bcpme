<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES TRANCHES HORAIRES',path:'/tranches'}" :link_2="'LISTE DES TRANCHES HORAIRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES TRANCHES HORAIRES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h2>GESTION DES TRANCHES HORAIRES</h2>
                        <router-link class="btn btn-primary" to="/tranches/create"><i class="demo-pli-add me-1 fs-5"></i> NOUVELLE TRANCHE HORAIRE</router-link>
                    </div>
                    <div>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>DEBUT</th>
                                    <th>FIN</th>
                                    <th>DUREE</th>
                                    <th>-</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="tranche in tranches" :key="tranche.id">
                                    <td>{{ tranche.start }}</td>
                                    <td>{{ tranche.end }}</td>
                                    <td>{{ tranche.gap }}</td>
                                    <td>-</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </template>
    </Display>

</template>

<script>
import {mapActions} from 'vuex'
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
export default {
    name:"TRANCHEIndex",
    ...mapActions({
        logout:"logOut"
    }),
    components:{
        Display,
        PageHeader,
        BreadCrumb,

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des tranches',
            tranches:[],
        }
    },
    methods:{
      async load(){
            //await this.$store.dispatch('ecoleCreate')
            await this.api.get('/api/tranches')
            .then((res)=>{
                console.log(res.data);
                this.tranches = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                //this.$router.push({path:'/login'});
            })

        }
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>

</style>
