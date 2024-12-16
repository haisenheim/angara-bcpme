import ProgrammesIndex from "../Components/Pages/Programmes/Index.vue"
import ProgrammesCreate from "../Components/Pages/Programmes/Create.vue";
import ProgrammesShow from "../Components/Pages/Programmes/Show.vue"
export default[
    {
        name: "programmes_index",
        path: "/programmes",
        component: ProgrammesIndex,
        meta: {
            middleware: "auth",
            title: `Liste des programmes`
        }
    },
    {
        name: "programmes_create",
        path: "/programmes/create",
        component: ProgrammesCreate,
        meta: {
            middleware: "auth",
            title: `Nouvel programme`
        }
    },
    {
        name: "programmes_show",
        path: "/programmes/show/:tkn",
        props: true,
        component: ProgrammesShow,
        meta: {
            middleware: "auth",
            title: `Programme`
        }
    },
]
