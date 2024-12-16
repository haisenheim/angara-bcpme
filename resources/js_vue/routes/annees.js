import AnneesIndex from "../Components/Pages/Annees/Index.vue"
import AnneesCreate from "../Components/Pages/Annees/Create.vue";
import AnneesShow from "../Components/Pages/Annees/Show.vue"
export default[
    {
        name: "annees_index",
        path: "/annees",
        component: AnneesIndex,
        meta: {
            middleware: "auth",
            title: `Liste des annees`
        }
    },
    {
        name: "annees_create",
        path: "/annees/create",
        component: AnneesCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle annee`
        }
    },
    {
        name: "annees_show",
        path: "/annees/show/:tkn",
        props: true,
        component: AnneesShow,
        meta: {
            middleware: "auth",
            title: `Annee`
        }
    },
]
