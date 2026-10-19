<script setup>
/**
 * Gabarit CRUD générique des écrans d'administration (agences, banques, organismes, secteurs,
 * délégation de pouvoir, modèles de documents, types de fichiers, pièces exigibles, utilisateurs).
 * Indicateurs, colonnes, filtres et actions sont décrits par le contrôleur ; création / édition /
 * suppression / activation passent par les routes existantes.
 */
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Icon from '@/Components/ui/Icon.vue';
import ResourceTable from '@/Components/admin/ResourceTable.vue';
import ActionButtons from '@/Components/admin/ActionButtons.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

const props = defineProps({
    title: String,
    subtitle: { type: String, default: null },
    notice: { type: String, default: null },
    countLabel: { type: String, default: 'élément(s)' },
    stats: { type: Array, default: () => [] },
    table: { type: Object, required: true },
    actions: { type: Array, default: () => [] },
    // Action ouverte au chargement (ex. route « edit » : dialogue de modification pré-rempli).
    autoAction: { type: Object, default: null },
});

const autoOpened = ref(props.autoAction);

const tones = {
    success: 'text-emerald-700',
    danger: 'text-error',
    info: 'text-blue-700',
    neutral: 'text-on-surface',
};
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length" #actions>
            <ActionButtons :actions="actions" />
        </template>
    </PageHeader>

    <div v-if="notice" class="flex items-start gap-3 rounded-xl border border-surface-container-highest bg-surface-container-low p-4 text-xs leading-relaxed text-on-surface-variant">
        <Icon name="info" class="text-secondary" />
        <p>{{ notice }}</p>
    </div>

    <div v-if="stats.length" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div v-for="s in stats" :key="s.label" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ s.label }}</span>
            <div class="mt-1 text-2xl font-bold tracking-tight" :class="tones[s.tone] ?? tones.neutral">{{ s.value }}</div>
        </div>
    </div>

    <ResourceTable :table="table" :count-label="countLabel" />

    <ActionDialog v-if="autoOpened" :key="autoOpened.key" :action="autoOpened" @close="autoOpened = null" />
</template>
