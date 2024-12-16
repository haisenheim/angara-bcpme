import TuteursIndex from "../Components/Pages/Tuteurs/Index.vue"
import TuteursCreate from "../Components/Pages/Tuteurs/Create.vue";
import TuteursShow from "../Components/Pages/Tuteurs/Show.vue"
export default[
    {
        name: "tuteurs_index",
        path: "/tuteurs",
        component: TuteursIndex,
        meta: {
            middleware: "auth",
            title: `Liste des tuteurs`
        }
    },
    {
        name: "tuteurs_create",
        path: "/tuteurs/create",
        component: TuteursCreate,
        meta: {
            middleware: "auth",
            title: `Nouveau tuteur`
        }
    },
    {
        name: "tuteurs_show",
        path: "/tuteurs/show/:tkn",
        props: true,
        component: TuteursShow,
        meta: {
            middleware: "auth",
            title: `Tuteur`
        }
    },
]
