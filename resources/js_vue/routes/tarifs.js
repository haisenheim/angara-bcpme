import TarifsIndex from "../Components/Pages/Tarifs/Index.vue"
import TarifsCreate from "../Components/Pages/Tarifs/Create.vue";
import TarifsShow from "../Components/Pages/Tarifs/Show.vue"
export default[
    {
        name: "tarifs_index",
        path: "/tarifs",
        component: TarifsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des tarifs`
        }
    },
    {
        name: "tarifs_create",
        path: "/tarifs/create",
        component: TarifsCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle tarif`
        }
    },
    {
        name: "tarifs_show",
        path: "/tarifs/show/:tkn",
        props: true,
        component: TarifsShow,
        meta: {
            middleware: "auth",
            title: `Tarif`
        }
    },
]
