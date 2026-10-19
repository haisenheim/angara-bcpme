<script setup>
/**
 * File de validation (chef d'agence, chef de filière) : tableau paginé côté serveur et,
 * le cas échéant, onglets vers les autres files du même profil (avec compteurs).
 */
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: null },
    countLabel: { type: String, default: 'élément(s)' },
    table: { type: Object, required: true },
    queues: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle" />

    <nav v-if="queues.length > 1" class="flex items-center gap-1 overflow-x-auto border-b border-surface-container-highest text-xs font-medium" aria-label="Files de validation">
        <Link
            v-for="q in queues"
            :key="q.key"
            :href="q.href"
            class="flex items-center gap-1.5 whitespace-nowrap border-b-2 px-4 py-2.5 transition-colors"
            :class="q.active ? 'rounded-t-md border-primary bg-surface-container-lowest font-bold text-on-surface' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
        >
            <Icon :name="q.icon" class="text-base" />
            {{ q.label }}
            <span v-if="q.count" class="ml-1 rounded-full bg-secondary/15 px-1.5 text-[10px] font-bold text-secondary">{{ q.count }}</span>
        </Link>
    </nav>

    <DataTable :table="table" :count-label="countLabel" />
</template>
