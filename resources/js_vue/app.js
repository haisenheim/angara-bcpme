import "./bootstrap";
import "~/assets/css/bootstrap.min.css";
//import "bootstrap/dist/css/bootstrap.min.css"
import "~/assets/css/nifty.min.css";
import "~/assets/css/demo-purpose/demo-icons.min.css";
import "~/assets/premium/icon-sets/icons/line-icons/premium-line-icons.css"
import "~/assets/vendors/popperjs/popper.min";

import "~/css/style.css";
import 'primevue/resources/themes/lara-light-blue/theme.css'

import { createApp } from "vue";
import mitt from 'mitt'
import {LoadingPlugin} from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import PrimeVue from "primevue/config";
import { createToaster } from "@meforma/vue-toaster";
import Display from '@/Components/Layout/Display.vue';
import PageHeader from '@/Components/Layout/Includes/PageHeader.vue';
import BreadCrumb from '@/Components/Layout/Includes/Breadcrumb.vue';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import { Bar,Pie } from 'vue-chartjs';
import { AgGridVue } from "ag-grid-vue3";
import Image from 'primevue/image';

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-balham.css"; // Optional Theme applied to the grid

import App from "./App.vue";

import store from "./store";
import router from "./router";
import apiClient from "./api";

const multipart = {
    headers:{
        'Content-Type':'multipart/form-data'
    }
};

const editorConfig = {
    language: 'fr',
    toolbar: {
        items: [
            'bold',
            'italic',
            'undo',
            'redo',
            'heading',
            '|',
            'alignment',
            'link',
            'bulletedList',
            'numberedList',
            'blockQuote',
        ]
    }
  }

  const emitter = mitt();

const vuetify = createVuetify({
    components,
    directives,
  })


store.dispatch("attempt").then(() => {
    const app = createApp(App);
    const toaster = createToaster({ position:'top-right'});
     app.use(store);
     app.use(router);
     app.use(LoadingPlugin);
     app.use(vuetify);
     app.use(PrimeVue,{
        locale:{
            emptySearchMessage:'Non trouvé!'
        },
        pt: {
            autocomplete: {
                input: { class: 'form-control' }
            }
        }
     });
     app.component('BreadCrumb',BreadCrumb)
        .component('Display',Display)
        .component('PageHeader',PageHeader)
        .component('Bar',Bar)
        .component('Pie',Pie)
        .component('AutoComplete',AutoComplete)
        .component('Dialog',Dialog)
        .component('Image',Image)
        .component('AgGridVue',AgGridVue)
     app.config.globalProperties.editorConfig = editorConfig
     app.config.globalProperties.api = apiClient;
     app.config.globalProperties.toaster = toaster;
     app.config.globalProperties.multipart = multipart;
     app.config.globalProperties.emitter = emitter
     app.mount('#app');
 }).catch(()=>router.push('/login'));

