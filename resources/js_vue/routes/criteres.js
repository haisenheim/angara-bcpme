import CriteresIndex from "../Components/Pages/Criteres/Index.vue"
import CriteresCreate from "../Components/Pages/Criteres/Create.vue";
import CriteresShow from "../Components/Pages/Criteres/Show.vue"
export default[
    {
        name: "criteres_index",
        path: "/criteres",
        component: CriteresIndex,
        meta: {
            middleware: "auth",
            title: `Liste des criteres`
        }
    },
    {
        name: "criteres_create",
        path: "/criteres/create",
        component: CriteresCreate,
        meta: {
            middleware: "auth",
            title: `Nouveau critere`
        }
    },
    {
        name: "criteres_show",
        path: "/criteres/show/:tkn",
        props: true,
        component: CriteresShow,
        meta: {
            middleware: "auth",
            title: `Annee`
        }
    },
]
