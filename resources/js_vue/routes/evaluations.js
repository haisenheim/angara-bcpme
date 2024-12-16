import EvaluationsIndex from "../Components/Pages/Evaluations/Index.vue"
import EvaluationsCreate from "../Components/Pages/Evaluations/Create.vue";
import EvaluationsShow from "../Components/Pages/Evaluations/Show.vue"
export default[
    {
        name: "evaluations_index",
        path: "/evaluations",
        component: EvaluationsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des evaluations`
        }
    },
    {
        name: "evaluations_create",
        path: "/evaluations/create",
        component: EvaluationsCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle evaluation`
        }
    },
    {
        name: "evaluations_show",
        path: "/evaluations/show/:tkn",
        props: true,
        component: EvaluationsShow,
        meta: {
            middleware: "auth",
            title: `Salle`
        }
    },
]
