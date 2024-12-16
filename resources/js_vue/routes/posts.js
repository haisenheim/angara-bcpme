import PostsIndex from "../Components/Pages/Posts/Index.vue"
import PostsCreate from "../Components/Pages/Posts/Create.vue";
import PostsShow from "../Components/Pages/Posts/Show.vue"
export default[
    {
        name: "posts_index",
        path: "/posts",
        component: PostsIndex,
        meta: {
            middleware: "auth",
            title: `Liste des posts`
        }
    },
    {
        name: "posts_create",
        path: "/posts/create",
        component: PostsCreate,
        meta: {
            middleware: "auth",
            title: `Nouveau post`
        }
    },
    {
        name: "posts_show",
        path: "/posts/show/:tkn",
        props: true,
        component: PostsShow,
        meta: {
            middleware: "auth",
            title: `Post`
        }
    },
]
