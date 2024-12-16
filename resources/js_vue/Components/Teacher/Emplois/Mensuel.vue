<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'HONORAIRES',path:'#'}" :link_2="'MON HISTORIQUE DU MOIS'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'MON HISTORIQUE DU MOIS'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">

                </div>
            </div>
        </template>
    </Display>

</template>

<script>

export default {
    name:"MENSUELIndex",
    props: ['mois_id'],
    data(){
        return {
            user:this.$store.state.user,
            description:'Historique du mois',
			items: [],
        }
    },
    computed:{
    },
    methods:{
      async load(){
        let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
        await this.api.get('/api/prof/honoraires/'+this.mois_id)
            .then((res)=>{
                this.items = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
                loader.hide();
            })
      },
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>

</style>
