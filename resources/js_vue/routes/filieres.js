import FilieresIndex from "../Components/Pages/Filieres/Index.vue"
import FilieresCreate from "../Components/Pages/Filieres/Create.vue";
import FilieresShow from "../Components/Pages/Filieres/Show.vue"
export default[
    {
        name: "filieres_index",
        path: "/filieres",
        component: FilieresIndex,
        meta: {
            middleware: "auth",
            title: `Liste des filieres`
        }
    },
    {
        name: "filieres_create",
        path: "/filieres/create",
        component: FilieresCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle filiere`
        }
    },
    {
        name: "filieres_show",
        path: "/filieres/show/:tkn",
        props: true,
        component: FilieresShow,
        meta: {
            middleware: "auth",
            title: `Filiere`
        }
    },
]
