<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';

defineProps({
    title: String,
    subtitle: String,
    table: Object,
});
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle" />
    <DataTable :table="table" count-label="dossier(s)" />
</template>
