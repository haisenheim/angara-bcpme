import LivresIndex from "../Components/Pages/Livres/Index.vue"
import LivresCreate from "../Components/Pages/Livres/Create.vue";
import LivresShow from "../Components/Pages/Livres/Show.vue"
export default[
    {
        name: "livres_index",
        path: "/bibliotheque",
        component: LivresIndex,
        meta: {
            middleware: "auth",
            title: `Liste des livres`
        }
    },
    {
        name: "livres_create",
        path: "/livres/create",
        component: LivresCreate,
        meta: {
            middleware: "auth",
            title: `Nouveau livre`
        }
    },
    {
        name: "livres_show",
        path: "/livres/show/:tkn",
        props: true,
        component: LivresShow,
        meta: {
            middleware: "auth",
            title: `Livre`
        }
    },
]
