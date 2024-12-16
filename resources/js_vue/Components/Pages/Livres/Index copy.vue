<template>
    <Display>
        <template v-slot:breadcrumb>
          <BreadCrumb :link_1="{name:'GESTION DES LIVRES',path:'/livres'}" :link_2="'LISTE DES LIVRES'"></BreadCrumb>
        </template>
        <template v-slot:page-header>
            <PageHeader :p="description" :h1="'GESTION DES LIVRES'" ></PageHeader>
        </template>
        <template v-slot:content>
            <div class="card_">
                <DataView :value="livres" :layout="layout">
                    <template #header>
                        <div class="flex justify-content-end">
                            <DataViewLayoutOptions v-model="layout" />
                        </div>
                    </template>
                    <template #list="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index" class="col-12">
                                <div class="flex flex-column sm:flex-row sm:align-items-center p-4 gap-3" :class="{ 'border-top-1 surface-border': index !== 0 }">
                                    <div class="md:w-10rem relative">
                                        <img class="block xl:block mx-auto border-round w-full" :src="item.photo" :alt="item.name" />
                                        <Tag :value="item.active" :severity="getSeverity(item)" class="absolute" style="left: 4px; top: 4px"></Tag>
                                    </div>
                                    <div class="flex flex-column md:flex-row justify-content-between md:align-items-center flex-1 gap-4">
                                        <div class="flex flex-row md:flex-column justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="font-medium text-secondary text-sm">{{ item.domaine }}</span>
                                                <div class="text-lg font-medium text-900 mt-2">{{ item.name }}</div>
                                            </div>
                                            <div class="surface-100 p-1" style="border-radius: 30px">
                                                <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2" style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                    <span class="text-900 font-medium text-sm">{{ item.auteur }}</span>
                                                    <i class="pli-people text-yellow-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-column md:align-items-end gap-5">
                                            <span class="text-xl font-semibold text-900">${{ item.annee }}</span>
                                            <div class="flex flex-row-reverse md:flex-row gap-2">
                                                <Button icon="pi pi-heart" outlined></Button>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template #grid="slotProps">
                        <div class="grid grid-nogutter">
                            <div v-for="(item, index) in slotProps.items" :key="index" class="card col-12 sm:col-6 md:col-4 xl:col-6 p-2">
                                <div class="p-4 border-1 surface-border card-body border-round flex flex-column">
                                    <div class="surface-50 flex justify-content-center border-round p-3">
                                        <div class="relative mx-auto">
                                            <img class="border-round w-full" :src="item.photo" :alt="item.name" style="max-width: 300px"/>
                                            <Tag :value="item.active" :severity="getSeverity(item)" class="absolute" style="left: 4px; top: 4px"></Tag>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <div class="flex flex-row justify-content-between align-items-start gap-2">
                                            <div>
                                                <span class="font-medium text-secondary text-sm">{{ item.domaine }}</span>
                                                <div class="text-lg font-medium text-900 mt-1">{{ item.name }}</div>
                                            </div>
                                            <div class="surface-100 p-1" style="border-radius: 30px">
                                                <div class="surface-0 flex align-items-center gap-2 justify-content-center py-1 px-2" style="border-radius: 30px; box-shadow: 0px 1px 2px 0px rgba(0, 0, 0, 0.04), 0px 1px 2px 0px rgba(0, 0, 0, 0.06)">
                                                    <span class="text-900 font-medium text-sm">{{ item.auteur }}</span>
                                                    <i class="pi pi-star-fill text-yellow-500"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </DataView>
            </div>
        </template>
    </Display>

</template>

<script>
import {mapActions} from 'vuex'
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
import DataView from 'primevue/dataview';
import DataViewLayoutOptions from 'primevue/dataviewlayoutoptions'   // optional

export default {
    name:"LivreIndex",
    ...mapActions({
        logout:"logOut"
    }),
    components:{
        Display,
        PageHeader,
        BreadCrumb,
        DataView,
        DataViewLayoutOptions

    },
    data(){
        return {
            user:this.$store.state.user,
            description:'Liste des livres de l\'ecole',
            livres:[],
            layout:'grid',
        }
    },
    methods:{
      async load(){
            await this.api.get('/api/livres')
            .then((res)=>{
                console.log(res.data);
                this.livres = res.data;
            })
            .catch((err)=>{
                console.log(err);
            })
            .finally(()=>{
            })

        },
        getSeverity(livre){
            switch (livre.active) {
                case 1:
                    return 'disponible';

                case 0:
                    return 'indisponible';
                default:
                    return null;
            }
        }
    },
    mounted(){
        this.load();
    }

}
</script>

<style scoped>

</style>


