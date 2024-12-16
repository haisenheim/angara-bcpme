<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES COMPTES',path:'/profile'}" :link_2="'mon profil'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="'Edition du profil'" :h1="'Mon profil'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card">
                <div class="card-body">MON PROFIL</div>
            </div>
        </template>
    </Display>
</template>
<script>
import avatar from '~/img/avatar.png';
export default {
    name:"Profile",
    components:{
    },
    data(){
        return {
            me:{},
        }
    },
    computed:{
    },
    methods:{
        changePdf(event){
            this.pdf = event.target.files[0];
        },
        async load(){
            let loader = this.$loading.show({
                    container: this.fullPage ? null : this.$refs.formContainer,
                    canCancel: false,
                });
            await this.api.get('/api/me')
                        .then((res)=>{
                            console.log(res.data)
                        })
                        .catch((err)=>console.error(err))
                        .finally(()=>loader.hide());
        },
    },
    mounted(){

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











