<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';

defineProps({
    title: String,
    subtitle: String,
    countLabel: { type: String, default: 'entreprise(s)' },
    table: Object,
    actions: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length" #actions>
            <a v-for="a in actions" :key="a.href" :href="a.href" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-black">
                <span class="material-symbols-outlined text-base">{{ a.icon }}</span>{{ a.label }}
            </a>
        </template>
    </PageHeader>
    <DataTable :table="table" :count-label="countLabel" />
</template>
