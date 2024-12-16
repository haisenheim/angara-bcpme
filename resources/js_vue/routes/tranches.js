import TranchesIndex from "../Components/Pages/Tranches/Index.vue"
import TranchesCreate from "../Components/Pages/Tranches/Create.vue";
import TranchesShow from "../Components/Pages/Tranches/Show.vue"
export default[
    {
        name: "tranches_index",
        path: "/tranches",
        component: TranchesIndex,
        meta: {
            middleware: "auth",
            title: `Liste des tranches`
        }
    },
    {
        name: "tranches_create",
        path: "/tranches/create",
        component: TranchesCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle tranche`
        }
    },
    {
        name: "tranches_show",
        path: "/tranches/show/:tkn",
        props: true,
        component: TranchesShow,
        meta: {
            middleware: "auth",
            title: `Tranche`
        }
    },
]
