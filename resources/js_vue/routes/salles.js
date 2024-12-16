import SallesIndex from "../Components/Pages/Salles/Index.vue"
import SallesCreate from "../Components/Pages/Salles/Create.vue";
import SallesShow from "../Components/Pages/Salles/Show.vue"
export default[
    {
        name: "salles_index",
        path: "/salles",
        component: SallesIndex,
        meta: {
            middleware: "auth",
            title: `Liste des salles`
        }
    },
    {
        name: "salles_create",
        path: "/salles/create",
        component: SallesCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle salle`
        }
    },
    {
        name: "salles_show",
        path: "/salles/show/:tkn",
        props: true,
        component: SallesShow,
        meta: {
            middleware: "auth",
            title: `Salle`
        }
    },
]
