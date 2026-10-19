<script setup>
import { Head } from '@inertiajs/vue3';
import Button from '@/Components/ui/Button.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';

defineProps({
    table: Object,
    simulatorUrl: String,
});
</script>

<template>
    <Head title="Scénarios enregistrés" />
    <PageHeader title="Scénarios enregistrés" subtitle="Vos simulations de crédit enregistrées.">
        <template #actions>
            <Button :href="simulatorUrl" variant="primary" icon="calculate">Nouvelle simulation</Button>
        </template>
    </PageHeader>
    <DataTable :table="table" count-label="scénario(s)" />
</template>
