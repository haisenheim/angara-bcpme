<template>
    <div>
        <h1 class="page-title mb-0 mt-2 fs-3">{{ h1 }}</h1>
        <p v-if="p!==undefined" class="lead">
            {{ p }}
        </p>
    </div>
</template>
<script>
    export default{
        name:'PageHeader',
        props:{
            'h1':String,
            'p':String,
        }
    }

</script>
