<script setup>
/**
 * Tableau de bord générique : les indicateurs affichés sont décrits par le serveur (kpis),
 * les valeurs arrivent en différé (Inertia::defer) pour un affichage immédiat de la page.
 */
import { computed } from 'vue';
import { Deferred, Head, Link } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Card from '@/Components/ui/Card.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import { badgeVariant } from '@/utils/badge';

const props = defineProps({
    title: String,
    kpis: Array,
    hasTodos: Boolean,
    hasInsights: Boolean,
    stats: Object,
    todos: Array,
    insights: Object,
});

const tones = {
    warning: 'border-amber-200 bg-amber-50/70',
    info: 'border-blue-200 bg-blue-50/60',
    success: 'border-secondary/30 bg-brand-subtle/60',
};

const pipelineSteps = [
    ['exploitation', 'Exploitation'], ['juridique', 'Juridique'], ['engagements', 'Engagements'],
    ['risques', 'Risques'], ['direction', 'Direction'], ['clos', 'Clôturés'],
];
const structurationSteps = [
    ['structure', 'Structurés', 'bg-secondary'], ['en_cours', 'En cours', 'bg-blue-500'],
    ['attente', 'En attente', 'bg-outline-variant'], ['rejetee', 'Rejetés', 'bg-error'],
];
const max = (values) => Math.max(1, ...values);
const pipelineMax = computed(() => (props.insights ? max(pipelineSteps.map(([k]) => props.insights.pipeline[k] ?? 0)) : 1));
const trendMax = computed(() => (props.insights ? max(props.insights.trends.map((t) => t.count)) : 1));
const structTotal = computed(() => (props.insights ? structurationSteps.reduce((n, [k]) => n + (props.insights.structuration[k] ?? 0), 0) : 0));
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" subtitle="Vue d'ensemble de votre activité et des actions attendues." />

    <!-- Indicateurs -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-4">
        <component
            :is="kpi.href ? Link : 'div'"
            v-for="kpi in kpis"
            :key="kpi.key"
            :href="kpi.href"
            class="rounded-xl border p-4 shadow-xs transition-shadow"
            :class="[tones[kpi.tone] ?? 'border-surface-container-highest bg-surface-container-lowest', kpi.href ? 'hover:shadow-sm' : '']"
        >
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ kpi.label }}</span>
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-subtle text-brand"><Icon :name="kpi.icon" class="text-lg" /></span>
            </div>
            <Deferred data="stats">
                <template #fallback><div class="mt-2 h-7 w-16 animate-pulse rounded bg-surface-container-high"></div></template>
                <div class="mt-2 text-2xl font-bold tracking-tight text-on-surface">{{ stats?.[kpi.key] ?? '—' }}</div>
            </Deferred>
        </component>
    </div>

    <!-- À traiter maintenant (responsables de pôle) -->
    <Card v-if="hasTodos" title="À traiter maintenant">
        <Deferred data="todos">
            <template #fallback><div class="h-24 animate-pulse rounded-lg bg-surface-container-low"></div></template>
            <ul class="divide-y divide-surface-container-highest text-xs">
                <li v-for="t in todos" :key="t.token">
                    <component :is="t.href ? Link : 'div'" :href="t.href" class="flex items-center justify-between gap-3 py-2.5 hover:bg-surface-container-low/50">
                        <div class="flex min-w-0 items-center gap-3">
                            <StatusBadge v-if="t.kind" :variant="badgeVariant(t.kind.variant)">{{ t.kind.label }}</StatusBadge>
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-on-surface">{{ t.entreprise }}</div>
                                <div class="truncate text-[11px] text-on-surface-variant">{{ t.programmes }}</div>
                            </div>
                        </div>
                        <span class="shrink-0 text-[11px] text-on-surface-variant">{{ t.when_human }}</span>
                    </component>
                </li>
                <li v-if="!todos?.length" class="py-6 text-center text-on-surface-variant">Aucune action en attente.</li>
            </ul>
        </Deferred>
    </Card>

    <!-- Direction générale -->
    <Deferred v-if="hasInsights" data="insights">
        <template #fallback><div class="h-64 animate-pulse rounded-xl bg-surface-container-low"></div></template>
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
            <Card title="Dossiers par étape du circuit">
                <div class="space-y-2.5 text-xs">
                    <div v-for="[key, label] in pipelineSteps" :key="key" class="flex items-center gap-3">
                        <span class="w-28 shrink-0 text-on-surface-variant">{{ label }}</span>
                        <div class="h-5 flex-1 overflow-hidden rounded bg-surface-container-low">
                            <div class="h-full rounded" :class="key === 'clos' ? 'bg-brand' : 'bg-secondary'" :style="{ width: `${(insights.pipeline[key] / pipelineMax) * 100}%` }"></div>
                        </div>
                        <span class="w-8 text-right font-bold text-on-surface">{{ insights.pipeline[key] }}</span>
                    </div>
                </div>
            </Card>
            <Card title="Structuration des clients">
                <div class="mb-3 flex h-4 overflow-hidden rounded">
                    <div v-for="[key, , color] in structurationSteps" :key="key" :class="color" :style="{ width: structTotal ? `${(insights.structuration[key] / structTotal) * 100}%` : '0' }"></div>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div v-for="[key, label, color] in structurationSteps" :key="key" class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" :class="color"></span>
                        <span class="text-on-surface-variant">{{ label }}</span>
                        <span class="ml-auto font-bold text-on-surface">{{ insights.structuration[key] }}</span>
                    </div>
                </div>
            </Card>
            <Card title="Dossiers validés par mois">
                <div class="flex h-40 items-end gap-3">
                    <div v-for="t in insights.trends" :key="t.label" class="flex flex-1 flex-col items-center gap-1">
                        <span class="text-[11px] font-bold text-on-surface">{{ t.count }}</span>
                        <div class="w-full rounded-t bg-secondary/80" :style="{ height: `${(t.count / trendMax) * 110}px` }"></div>
                        <span class="text-[10px] text-on-surface-variant">{{ t.label }}</span>
                    </div>
                </div>
            </Card>
            <Card title="En attente de votre décision">
                <ul class="divide-y divide-surface-container-highest text-xs">
                    <li v-for="d in insights.attente_direction" :key="d.token">
                        <Link :href="d.href" class="flex items-center justify-between gap-3 py-2.5 hover:bg-surface-container-low/50">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-on-surface">{{ d.entreprise }}</div>
                                <div class="truncate text-[11px] text-on-surface-variant">{{ d.programmes }}</div>
                            </div>
                            <span class="shrink-0 text-[11px] text-on-surface-variant">{{ d.since }}</span>
                        </Link>
                    </li>
                    <li v-if="!insights.attente_direction.length" class="py-6 text-center text-on-surface-variant">Aucun dossier en attente.</li>
                </ul>
            </Card>
        </div>
    </Deferred>
</template>
