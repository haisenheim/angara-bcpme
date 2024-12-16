import ExamensIndex from "../Components/Pages/Examens/Index.vue"
import ExamensCreate from "../Components/Pages/Examens/Create.vue";
import ExamensShow from "../Components/Pages/Examens/Show.vue"
import ExamensNotes from "../Components/Pages/Examens/Notes.vue"

//prof
import ProfExamensIndex from "../Components/Teacher/Examens/Index.vue"
import ProfExamensShow from "../Components/Teacher/Examens/Show.vue"
import ProfExamensNotes from "../Components/Teacher/Examens/Notes.vue"
export default[
    {
        name: "examens_index",
        path: "/examens",
        component: ExamensIndex,
        meta: {
            middleware: "auth",
            title: `Liste des examens`,
            guard:'admin',
        }
    },
    {
        name: "examens_notes",
        path: "/notes",
        component: ExamensNotes,
        meta: {
            middleware: "auth",
            title: `Liste des notes`,
            guard:'admin'
        }
    },
    {
        name: "examens_create",
        path: "/examens/create",
        component: ExamensCreate,
        meta: {
            middleware: "auth",
            title: `Nouvelle examen`,
            guard:'admin'
        }
    },
    {
        name: "examens_show",
        path: "/examens/show/:tkn",
        props: true,
        component: ExamensShow,
        meta: {
            middleware: "auth",
            title: `Examen`,
            guard:'admin',
        }
    },

    //prof
    {
        name: "prof_examens_index",
        path: "/prof/examens",
        component: ProfExamensIndex,
        meta: {
            middleware: "auth",
            title: `Liste des examens`,
            guard:'teacher',
        }
    },
    {
        name: "prof_examens_notes",
        path: "/prof/notes",
        component: ProfExamensNotes,
        meta: {
            middleware: "auth",
            title: `Liste des notes`,
            guard:'teacher',
        }
    },
    {
        name: "prof_examens_show",
        path: "/prof/examens/show/:tkn",
        props: true,
        component: ProfExamensShow,
        meta: {
            middleware: "auth",
            title: `Examen`,
            guard:'teacher'
        }
    },
]
