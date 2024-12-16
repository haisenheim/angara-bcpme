<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'HONORAIRES',path:'#'}" :link_2="'MES HONORAIRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'MES HONORAIRES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card" style="max-width: 600px;">
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
                            <tr class="table-success">
                                <th>TOTAL</th>
                                <th><span class="float-right">{{ theures }}</span></th>
                                <th><span class="float-right">{{ thonoraires }}</span></th>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </Display>

</template>

<script>

export default {
    name:"HonorairesIndex",
    data(){
        return {
            user:this.$store.state.user,
            description:'Mes honoraires',

			length: '15',
			items: [],
            somme:0,
            reste:0,
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
        await this.api.get('/api/prof/honoraires')
            .then((res)=>{
                this.items = res.data.groups;
                this.somme = res.data.somme;
                this.reste = res.data.reste;
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
