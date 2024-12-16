import LaboratoiresIndex from "../Components/Pages/Laboratoires/Index.vue"
import LaboratoiresCreate from "../Components/Pages/Laboratoires/Create.vue";
import LaboratoiresShow from "../Components/Pages/Laboratoires/Show.vue"
export default[
    {
        name: "laboratoires_index",
        path: "/laboratoires",
        component: LaboratoiresIndex,
        meta: {
            middleware: "auth",
            title: `Liste des laboratoires`
        }
    },
    {
        name: "laboratoires_create",
        path: "/laboratoires/create",
        component: LaboratoiresCreate,
        meta: {
            middleware: "auth",
            title: `Nouveau laboratoire`
        }
    },
    {
        name: "laboratoires_show",
        path: "/laboratoires/show/:tkn",
        props: true,
        component: LaboratoiresShow,
        meta: {
            middleware: "auth",
            title: `Laboratoire`
        }
    },
]
