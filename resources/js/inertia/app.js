import './app.css';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import AppLayout from './Layouts/AppLayout.vue';
import FunLoader from './Components/feedback/FunLoader.vue';
import { installLoader } from './loader';

createInertiaApp({
    title: (title) => (title ? `${title} — BCPME ANGARA` : 'BCPME ANGARA'),
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue', { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        // Layout unique par défaut (persistant entre les pages).
        page.default.layout = page.default.layout ?? AppLayout;
        return page;
    },
    setup({ el, App, props, plugin }) {
        // Loader global monté à la racine : couvre aussi les pages sans layout (accueil, connexion).
        createApp({ render: () => [h(App, props), h(FunLoader)] }).use(plugin).mount(el);
        installLoader();
    },
    // Remplacé par le loader global (resources/js/inertia/loader.js).
    progress: false,
});
