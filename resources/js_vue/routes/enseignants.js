import EnseignantsIndex from "../Components/Pages/Enseignants/Index.vue"
import EnseignantsCreate from "../Components/Pages/Enseignants/Create.vue";
import EnseignantsShow from "../Components/Pages/Enseignants/Show.vue"
export default[
    {
        name: "enseignants_index",
        path: "/enseignants",
        component: EnseignantsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des enseignants`
        }
    },
    {
        name: "enseignants_create",
        path: "/enseignants/create",
        component: EnseignantsCreate,
        meta: {
            middleware: "auth",
            title: `Nouvel enseignant`
        }
    },
    {
        name: "enseignants_show",
        path: "/enseignants/show/:tkn",
        props: true,
        component: EnseignantsShow,
        meta: {
            middleware: "auth",
            title: `Enseignant`
        }
    },
]
