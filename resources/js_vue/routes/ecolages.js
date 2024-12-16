import EcolagesIndex from "../Components/Pages/Ecolages/Index.vue"
import EcolagesCreate from "../Components/Pages/Ecolages/Create.vue";
import EcolagesShow from "../Components/Pages/Ecolages/Show.vue"
export default[
    {
        name: "ecolages_index",
        path: "/ecolages",
        component: EcolagesIndex,
        meta: {
            middleware: "auth",
            title: `Liste des ecolages`
        }
    },
    {
        name: "ecolages_create",
        path: "/ecolages/create",
        component: EcolagesCreate,
        meta: {
            middleware: "auth",
            title: `Nouvel ecolage`
        }
    },
    {
        name: "ecolages_show",
        path: "/ecolages/show/:tkn",
        props: true,
        component: EcolagesShow,
        meta: {
            middleware: "auth",
            title: `Ecolage`
        }
    },
]
