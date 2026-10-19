<script setup>
/**
 * Pilotage : 5 familles d'indicateurs (opérationnel, portefeuille, risques, stratégique, programmes).
 * Une famille est calculée à la fois côté serveur (TableauDeBordService) ; onglets et filtres de période
 * rechargent la page via Inertia. Graphiques simples en CSS (barres horizontales ou colonnes).
 */
import { reactive } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';

const props = defineProps({
    familles: { type: Array, required: true },
    active: { type: String, required: true },
    filters: { type: Object, default: () => ({ from: '', to: '' }) },
    perimetre: { type: String, default: null },
    data: { type: Object, required: true },
    baseUrl: { type: String, required: true },
});

const values = reactive({ from: props.filters.from ?? '', to: props.filters.to ?? '' });

const visit = (famille, period = values) => {
    const query = { famille };
    if (period.from) query.from = period.from;
    if (period.to) query.to = period.to;
    router.get(props.baseUrl, query, { preserveState: true, preserveScroll: true, replace: true });
};
const reset = () => {
    values.from = '';
    values.to = '';
    visit(props.active);
};

const current = () => props.familles.find((f) => f.code === props.active);
const max = (items) => Math.max(1, ...items.map((i) => i.value));
const pct = (v, items) => `${Math.max(v > 0 ? 2 : 0, Math.round((v / max(items)) * 100))}%`;

const tones = {
    info: 'text-blue-700 bg-blue-50',
    success: 'text-emerald-700 bg-emerald-50',
    warning: 'text-amber-700 bg-amber-50',
    danger: 'text-error bg-error-container',
    neutral: 'text-secondary bg-brand-subtle',
};
const inputClass = 'rounded-lg border-surface-container-highest bg-surface-container-low text-xs focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head title="Tableaux de bord" />
    <PageHeader title="Tableaux de bord" subtitle="5 familles d'indicateurs. Sélectionnez une famille pour voir le détail.">
        <template v-if="perimetre" #actions>
            <span class="inline-flex items-center gap-1.5 rounded-lg border border-surface-container-highest bg-white px-3 py-2 text-xs text-on-surface-variant">
                <Icon name="location_on" class="text-base text-secondary" /> Périmètre : <strong class="text-on-surface">{{ perimetre }}</strong>
            </span>
        </template>
    </PageHeader>

    <!-- Filtres de période -->
    <form class="flex flex-wrap items-end gap-3 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs" @submit.prevent="visit(active)">
        <div class="flex flex-col gap-1">
            <label for="tdb-from" class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Du</label>
            <input id="tdb-from" v-model="values.from" type="date" :class="inputClass">
        </div>
        <div class="flex flex-col gap-1">
            <label for="tdb-to" class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">au</label>
            <input id="tdb-to" v-model="values.to" type="date" :class="inputClass">
        </div>
        <div class="ml-auto flex gap-2">
            <Button type="submit" variant="primary" icon="refresh">Recharger</Button>
            <Button icon="restart_alt" @click="reset">Réinitialiser</Button>
        </div>
    </form>

    <!-- Onglets familles -->
    <nav class="flex flex-wrap gap-1 border-b border-surface-container-highest" aria-label="Familles d'indicateurs">
        <button
            v-for="f in familles"
            :key="f.code"
            type="button"
            class="-mb-px border-b-2 px-4 py-2.5 text-xs font-semibold transition-colors"
            :class="f.code === active ? 'border-secondary text-secondary' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
            :aria-current="f.code === active ? 'page' : null"
            @click="visit(f.code)"
        >
            {{ f.label }}
        </button>
    </nav>
    <p class="text-xs text-on-surface-variant">{{ current()?.description }}</p>

    <!-- Indicateurs -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div v-for="k in data.kpis" :key="k.label" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <div class="flex items-start justify-between gap-2">
                <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ k.label }}</span>
                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full" :class="tones[k.tone] ?? tones.neutral"><Icon :name="k.icon" class="text-lg" /></span>
            </div>
            <div class="mt-2 text-2xl font-bold tracking-tight text-on-surface">{{ k.value }}</div>
        </div>
    </div>

    <div v-for="(n, i) in data.notes" :key="i" class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 text-xs text-blue-900">
        <Icon name="info" class="text-blue-700" />
        <p>{{ n }}</p>
    </div>

    <!-- Graphiques -->
    <div v-if="data.charts.length" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <Card v-for="c in data.charts" :key="c.title" :title="c.title" :class="c.layout === 'columns' ? 'lg:col-span-2' : ''">
            <p v-if="!c.items.length" class="py-6 text-center text-xs text-on-surface-variant">Aucune donnée.</p>
            <div v-else-if="c.layout === 'columns'" class="flex h-48 items-end gap-2">
                <div v-for="it in c.items" :key="it.label" class="flex h-full flex-1 flex-col items-center justify-end gap-1" :title="`${it.label} : ${it.display}`">
                    <span class="text-[10px] font-semibold text-on-surface">{{ it.display }}</span>
                    <div class="w-full rounded-t bg-secondary" :style="{ height: pct(it.value, c.items) }"></div>
                    <span class="truncate text-[10px] text-on-surface-variant">{{ it.label }}</span>
                </div>
            </div>
            <ul v-else class="space-y-2.5">
                <li v-for="it in c.items" :key="it.label" class="text-xs">
                    <div class="mb-1 flex justify-between gap-2">
                        <span class="truncate text-on-surface">{{ it.label }}</span>
                        <span class="shrink-0 font-semibold text-on-surface">{{ it.display }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-surface-container">
                        <div class="h-2 rounded-full bg-brand" :style="{ width: pct(it.value, c.items) }"></div>
                    </div>
                </li>
            </ul>
        </Card>
    </div>

    <!-- Tableaux -->
    <div v-if="data.tables.length" class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <Card v-for="t in data.tables" :key="t.title" :padded="false" :class="t.span === 2 ? 'lg:col-span-2' : ''">
            <h2 class="px-5 pt-5 text-xs font-semibold uppercase tracking-wider text-on-surface-variant">{{ t.title }}</h2>
            <div class="mt-3 overflow-x-auto">
                <table class="w-full text-xs">
                    <thead class="border-y border-surface-container-highest bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                        <tr>
                            <th v-for="(h, i) in t.headers" :key="h" class="px-4 py-2 font-semibold" :class="i > 0 ? 'text-right' : 'text-left'">{{ h }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-highest">
                        <tr v-for="(r, ri) in t.rows" :key="ri">
                            <td v-for="(cell, ci) in r" :key="ci" class="px-4 py-2.5" :class="ci > 0 ? 'text-right font-semibold text-on-surface' : 'text-on-surface'">{{ cell }}</td>
                        </tr>
                        <tr v-if="!t.rows.length">
                            <td :colspan="t.headers.length" class="px-4 py-8 text-center text-on-surface-variant">—</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </div>
</template>
