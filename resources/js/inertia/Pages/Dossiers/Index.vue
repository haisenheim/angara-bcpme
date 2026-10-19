<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    title: String,
    subtitle: String,
    table: Object,
    // Indicateurs optionnels (anciens endpoints fetchStats) : [{ label, value, icon, variant }]
    stats: { type: Array, default: null },
    // Liens d'en-tête optionnels : [{ label, href, icon }]
    links: { type: Array, default: () => [] },
});

const tone = {
    info: 'bg-blue-50 text-blue-700',
    success: 'bg-emerald-50 text-emerald-700',
    warning: 'bg-amber-50 text-amber-700',
    neutral: 'bg-surface-container-low text-on-surface-variant',
};
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="links.length" #actions>
            <Button v-for="l in links" :key="l.href" :href="l.href" :icon="l.icon">{{ l.label }}</Button>
        </template>
    </PageHeader>

    <div v-if="stats?.length" class="grid grid-cols-1 gap-3.5 sm:grid-cols-3">
        <div v-for="s in stats" :key="s.label" class="flex items-center gap-3 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <div class="flex h-10 w-10 items-center justify-center rounded-full" :class="tone[s.variant] ?? tone.neutral">
                <Icon :name="s.icon" class="text-xl" />
            </div>
            <div>
                <div class="text-xl font-bold text-on-surface">{{ s.value }}</div>
                <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ s.label }}</div>
            </div>
        </div>
    </div>

    <DataTable :table="table" count-label="dossier(s)" />
</template>
