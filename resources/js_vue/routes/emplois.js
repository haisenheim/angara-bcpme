import EmploisIndex from "../Components/Pages/Emplois/Index.vue"
import EmploisCreate from "../Components/Pages/Emplois/Create.vue";
import EmploisShow from "../Components/Pages/Emplois/Show.vue"

import ProfEmploisIndex from "../Components/Teacher/Emplois/Index.vue"
import ProfEmploisShow from "../Components/Teacher/Emplois/Show.vue"
import ProfHonoraires from "../Components/Teacher/Emplois/Honoraires.vue";
import ProfMensuel from "../Components/Teacher/Emplois/Mensuel.vue";

export default[
    {
        name: "emplois_index",
        path: "/emplois",
        component: EmploisIndex,
        meta: {
            middleware: "auth",
            title: `Liste des emplois`,
             guard:'admin'
        }
    },
    {
        name: "emplois_create",
        path: "/emplois/create",
        component: EmploisCreate,
        meta: {
            middleware: "auth",
            title: `Nouvel emploi de temps`,
             guard:'admin'
        }
    },
    {
        name: "emplois_show",
        path: "/emplois/:tkn",
        props: true,
        component: EmploisShow,
        meta: {
            middleware: "auth",
            title: `Emploi`,
             guard:'admin'
        }
    },

    //for teacher
    {
        name: "prof_emplois_index",
        path: "/prof/emplois",
        component: ProfEmploisIndex,
        meta: {
            middleware: "auth",
            title: `Mon des emplois`,
            guard:'teacher'
        }
    },
    {
        name: "prof_emplois_show",
        path: "/prof/emplois/:tkn",
        props: true,
        component: ProfEmploisShow,
        meta: {
            middleware: "auth",
            title: `Emploi`,
            guard:'teacher'
        }
    },

    {
        name: "prof_honoraires_index",
        path: "/prof/honoraires",
        component: ProfHonoraires,
        meta: {
            middleware: "auth",
            title: `Mes honoraires`,
            guard:'teacher'
        }
    },
    {
        name: "prof_honoraires_show",
        path: "/prof/honoraires/:mois_id",
        props: true,
        component: ProfMensuel,
        meta: {
            middleware: "auth",
            title: `Historique mensuel`,
            guard:'teacher'
        }
    },

]
