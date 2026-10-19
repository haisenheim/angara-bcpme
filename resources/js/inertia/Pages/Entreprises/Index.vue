<script setup>
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import DataTable from '@/Components/ui/DataTable.vue';
import Icon from '@/Components/ui/Icon.vue';

defineProps({
    title: String,
    subtitle: String,
    countLabel: { type: String, default: 'entreprise(s)' },
    table: Object,
    // Liens d'en-tête : [{ label, href, icon, variant? }]
    actions: { type: Array, default: () => [] },
    // Indicateurs optionnels au-dessus du tableau : [{ label, value, icon? }]
    stats: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length" #actions>
            <a
                v-for="a in actions" :key="a.href" :href="a.href"
                class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-xs font-semibold shadow-xs"
                :class="a.variant === 'secondary' ? 'border border-surface-container-highest bg-white text-on-surface hover:bg-surface-container-low' : 'bg-primary-container text-white hover:bg-black'"
            >
                <span class="material-symbols-outlined text-base">{{ a.icon }}</span>{{ a.label }}
            </a>
        </template>
    </PageHeader>
    <div v-if="stats.length" class="grid grid-cols-2 gap-3.5 xl:grid-cols-4">
        <div v-for="s in stats" :key="s.label" class="flex items-center gap-3 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <span v-if="s.icon" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-secondary/10 text-secondary">
                <Icon :name="s.icon" class="text-lg" />
            </span>
            <div>
                <div class="text-lg font-bold text-on-surface">{{ s.value }}</div>
                <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ s.label }}</div>
            </div>
        </div>
    </div>
    <DataTable :table="table" :count-label="countLabel" />
</template>
