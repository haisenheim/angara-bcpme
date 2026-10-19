<script setup>
/**
 * Catalogue des programmes d'appui, commun à tous les profils (contrat : ProgrammePagePresenter::index).
 * Les indicateurs (total, actifs, avec convention) arrivent en différé et suivent le filtre signataire.
 */
import { Deferred, Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    title: String,
    subtitle: String,
    table: Object,
    stats: Object,
    actions: { type: Array, default: () => [] },
});

const indicators = [
    { key: 'total', label: 'Programmes', icon: 'volunteer_activism' },
    { key: 'actifs', label: 'Programmes actifs', icon: 'task_alt' },
    { key: 'avec_convention', label: 'Avec convention', icon: 'contract' },
];
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length" #actions>
            <Link v-for="a in actions" :key="a.href" :href="a.href" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-black">
                <Icon :name="a.icon" class="text-base" />{{ a.label }}
            </Link>
        </template>
    </PageHeader>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div v-for="ind in indicators" :key="ind.key" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ ind.label }}</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-subtle text-brand"><Icon :name="ind.icon" class="text-lg" /></span>
            </div>
            <Deferred data="stats">
                <template #fallback><div class="mt-2 h-7 w-16 animate-pulse rounded bg-surface-container-high"></div></template>
                <div class="mt-2 text-2xl font-bold tracking-tight text-on-surface">{{ stats?.[ind.key] ?? '—' }}</div>
            </Deferred>
        </div>
    </div>

    <DataTable :table="table" count-label="programme(s)" />
</template>
