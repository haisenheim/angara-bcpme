import InscriptionsIndex from "../Components/Pages/Inscriptions/Index.vue"
import InscriptionsCreate from "../Components/Pages/Inscriptions/Create.vue";
import InscriptionsShow from "../Components/Pages/Inscriptions/Show.vue"
export default[
    {
        name: "inscriptions_index",
        path: "/inscriptions",
        component: InscriptionsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des inscriptions`
        }
    },
    {
        name: "inscriptions_create",
        path: "/inscriptions/create",
        component: InscriptionsCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle inscription`
        }
    },
    {
        name: "inscriptions_show",
        path: "/inscriptions/show/:tkn",
        props: true,
        component: InscriptionsShow,
        meta: {
            middleware: "auth",
            title: `Inscription`
        }
    },
]
