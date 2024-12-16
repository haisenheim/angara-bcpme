import EtudiantsIndex from "../Components/Pages/Etudiants/Index.vue"
import EtudiantsCreate from "../Components/Pages/Etudiants/Create.vue";
import EtudiantsShow from "../Components/Pages/Etudiants/Show.vue"
export default[
    {
        name: "etudiants_index",
        path: "/etudiants",
        component: EtudiantsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des etudiants`
        }
    },
    {
        name: "etudiants_create",
        path: "/etudiants/create",
        component: EtudiantsCreate,
        meta: {
            middleware: "auth",
            title: `Nouvel etudiant`
        }
    },
    {
        name: "etudiants_show",
        path: "/etudiants/show/:tkn",
        props: true,
        component: EtudiantsShow,
        meta: {
            middleware: "auth",
            title: `Etudiant`
        }
    },
]
