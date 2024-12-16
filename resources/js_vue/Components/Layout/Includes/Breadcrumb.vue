<template>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#">ACCUEIL</a></li>
            <li class="breadcrumb-item"><router-link :to="{path:link_1.path}">{{ link_1.name }}</router-link></li>
            <li class="breadcrumb-item active" aria-current="page">{{ link_2 }}</li>
        </ol>
    </nav>
    <!-- END : Breadcrumb -->
</template>
<script>
    export default{
        name:'Breadcrumb',
        props:{
            link_1:{
                'name':String,
                'path':String,
            },
            'link_2':String,
        }
    }
</script>
