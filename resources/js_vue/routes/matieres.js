import MatieresIndex from "../Components/Pages/Matieres/Index.vue"
import MatieresCreate from "../Components/Pages/Matieres/Create.vue";
import MatieresShow from "../Components/Pages/Matieres/Show.vue"
export default[
    {
        name: "matieres_index",
        path: "/matieres",
        component: MatieresIndex,
        meta: {
            middleware: "auth",
            title: `Liste des matieres`
        }
    },
    {
        name: "matieres_create",
        path: "/matieres/create",
        component: MatieresCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle matiere`
        }
    },
    {
        name: "matieres_show",
        path: "/matieres/show/:tkn",
        props: true,
        component: MatieresShow,
        meta: {
            middleware: "auth",
            title: `Matiere`
        }
    },
]
