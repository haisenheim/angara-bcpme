<script setup>
/**
 * Widget de tableau de bord décrit par le serveur (contrat : RendersInertiaDashboard, option « widgets »).
 *
 * widget : { key, type, title, span?, link?: { label, href }, empty?, columns?, series?, items? }
 * data   : valeurs différées de ce widget (widgetData[key]) ; les widgets statiques (links) utilisent widget.items.
 *
 * Types :
 *  - list     : [{ title, subtitle?, meta?, badge?: { label, variant }, href? }]
 *  - bars     : [{ label, value, tone? }]                         barres horizontales
 *  - stacked  : [{ label, value, tone? }]                         barre empilée + légende
 *  - columns  : [{ label, values: [n, …] }] + widget.series [{ label, tone }]   histogramme mensuel
 *  - metrics  : [{ label, value, progress?, tone? }]              valeurs clés (barre de progression optionnelle)
 *  - table    : { rows: [{ cells, href? }] } + widget.columns    (cellules : texte ou { badge, variant })
 *  - alerts   : [{ tone, icon?, title, text }]
 *  - links    : widget.items [{ label, icon, href }]              actions rapides
 * Les liens sont de simples <a> : la cible peut encore être un écran Blade.
 */
import { computed } from 'vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import { badgeVariant } from '@/utils/badge';

const props = defineProps({
    widget: { type: Object, required: true },
    data: { default: null },
});

const toneBar = {
    brand: 'bg-brand', success: 'bg-secondary', info: 'bg-blue-500', warning: 'bg-amber-500',
    danger: 'bg-error', neutral: 'bg-outline-variant', dark: 'bg-primary-container',
};
const toneAlert = {
    warning: 'border-amber-200 bg-amber-50/70 text-amber-900',
    danger: 'border-error/30 bg-error-container/60 text-on-error-container',
    info: 'border-blue-200 bg-blue-50/60 text-blue-900',
    success: 'border-secondary/30 bg-brand-subtle/60 text-on-surface',
};
const bar = (tone, fallback = 'bg-secondary') => toneBar[tone] ?? fallback;

const items = computed(() => {
    if (props.widget.type === 'links') return props.widget.items ?? [];
    if (props.widget.type === 'table') return props.data?.rows ?? [];
    return Array.isArray(props.data) ? props.data : [];
});
const maxValue = computed(() => Math.max(1, ...items.value.map((i) => Number(i.value) || 0)));
const total = computed(() => items.value.reduce((n, i) => n + (Number(i.value) || 0), 0));
const columnsMax = computed(() => Math.max(1, ...items.value.flatMap((p) => (p.values ?? []).map((v) => Number(v) || 0))));
const series = computed(() => props.widget.series ?? [{ label: '', tone: 'success' }]);
const alignClass = (col) => (col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : 'text-left');
</script>

<template>
    <Card :title="widget.title">
        <template v-if="widget.link" #actions>
            <a :href="widget.link.href" class="inline-flex items-center gap-1 text-[11px] font-semibold text-secondary hover:underline">
                {{ widget.link.label }} <Icon name="arrow_forward" class="text-sm" />
            </a>
        </template>

        <!-- Liste -->
        <ul v-if="widget.type === 'list'" class="divide-y divide-surface-container-highest text-xs">
            <li v-for="(item, i) in items" :key="i">
                <component :is="item.href ? 'a' : 'div'" :href="item.href" class="flex items-center justify-between gap-3 py-2.5" :class="item.href ? 'hover:bg-surface-container-low/50' : ''">
                    <div class="flex min-w-0 items-center gap-3">
                        <StatusBadge v-if="item.badge" :variant="badgeVariant(item.badge.variant)">{{ item.badge.label }}</StatusBadge>
                        <div class="min-w-0">
                            <div class="truncate font-semibold text-on-surface">{{ item.title }}</div>
                            <div v-if="item.subtitle" class="truncate text-[11px] text-on-surface-variant">{{ item.subtitle }}</div>
                        </div>
                    </div>
                    <span v-if="item.meta" class="shrink-0 text-[11px] text-on-surface-variant">{{ item.meta }}</span>
                </component>
            </li>
            <li v-if="!items.length" class="py-6 text-center text-on-surface-variant">{{ widget.empty ?? 'Aucun élément.' }}</li>
        </ul>

        <!-- Barres horizontales -->
        <div v-else-if="widget.type === 'bars'" class="space-y-2.5 text-xs">
            <div v-for="item in items" :key="item.label" class="flex items-center gap-3">
                <span class="w-32 shrink-0 truncate text-on-surface-variant" :title="item.label">{{ item.label }}</span>
                <div class="h-5 flex-1 overflow-hidden rounded bg-surface-container-low">
                    <div class="h-full rounded" :class="bar(item.tone)" :style="{ width: `${((Number(item.value) || 0) / maxValue) * 100}%` }"></div>
                </div>
                <span class="w-10 text-right font-bold text-on-surface">{{ item.value }}</span>
            </div>
            <p v-if="!items.length" class="py-6 text-center text-on-surface-variant">{{ widget.empty ?? 'Aucune donnée.' }}</p>
        </div>

        <!-- Barre empilée -->
        <div v-else-if="widget.type === 'stacked'">
            <div class="mb-3 flex h-4 overflow-hidden rounded bg-surface-container-low">
                <div v-for="item in items" :key="item.label" :class="bar(item.tone)" :style="{ width: total ? `${((Number(item.value) || 0) / total) * 100}%` : '0' }"></div>
            </div>
            <div class="grid grid-cols-2 gap-2 text-xs">
                <div v-for="item in items" :key="item.label" class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 shrink-0 rounded-full" :class="bar(item.tone)"></span>
                    <span class="text-on-surface-variant">{{ item.label }}</span>
                    <span class="ml-auto font-bold text-on-surface">{{ item.value }}</span>
                </div>
            </div>
        </div>

        <!-- Histogramme -->
        <div v-else-if="widget.type === 'columns'">
            <div class="flex h-44 items-end gap-3">
                <div v-for="point in items" :key="point.label" class="flex h-full flex-1 flex-col items-center justify-end gap-1">
                    <div class="flex w-full flex-1 items-end justify-center gap-1">
                        <div v-for="(v, s) in point.values" :key="s" class="flex flex-1 flex-col items-center justify-end">
                            <span class="text-[10px] font-bold text-on-surface">{{ v }}</span>
                            <div class="w-full rounded-t" :class="bar(series[s]?.tone)" :style="{ height: `${((Number(v) || 0) / columnsMax) * 110}px` }"></div>
                        </div>
                    </div>
                    <span class="whitespace-nowrap text-[10px] text-on-surface-variant">{{ point.label }}</span>
                </div>
            </div>
            <div v-if="series.length > 1 || series[0]?.label" class="mt-3 flex flex-wrap gap-4 text-[11px] text-on-surface-variant">
                <span v-for="s in series" :key="s.label" class="flex items-center gap-1.5"><span class="h-2.5 w-2.5 rounded-sm" :class="bar(s.tone)"></span>{{ s.label }}</span>
            </div>
        </div>

        <!-- Valeurs clés -->
        <dl v-else-if="widget.type === 'metrics'" class="divide-y divide-surface-container-highest text-xs">
            <div v-for="item in items" :key="item.label" class="py-2.5">
                <div class="flex items-center justify-between gap-3">
                    <dt class="text-on-surface-variant">{{ item.label }}</dt>
                    <dd class="font-bold text-on-surface">{{ item.value }}</dd>
                </div>
                <div v-if="item.progress !== undefined && item.progress !== null" class="mt-1.5 h-2 overflow-hidden rounded bg-surface-container-low">
                    <div class="h-full rounded" :class="bar(item.tone)" :style="{ width: `${Math.min(100, Math.max(0, item.progress))}%` }"></div>
                </div>
            </div>
        </dl>

        <!-- Tableau -->
        <div v-else-if="widget.type === 'table'" class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr><th v-for="col in widget.columns" :key="col.key" class="px-2 py-2 font-semibold" :class="alignClass(col)">{{ col.label }}</th></tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="(row, i) in items" :key="i" class="hover:bg-surface-container-low/50">
                        <td v-for="col in widget.columns" :key="col.key" class="px-2 py-2.5" :class="alignClass(col)">
                            <template v-for="cell in [row.cells[col.key]]" :key="col.key">
                                <StatusBadge v-if="cell && typeof cell === 'object' && cell.badge" :variant="badgeVariant(cell.variant)">{{ cell.badge }}</StatusBadge>
                                <a v-else-if="row.href && col === widget.columns[0]" :href="row.href" class="font-semibold text-on-surface hover:text-secondary hover:underline">{{ cell ?? '—' }}</a>
                                <span v-else :class="col === widget.columns[0] ? 'font-semibold text-on-surface' : 'text-on-surface'">{{ cell ?? '—' }}</span>
                            </template>
                        </td>
                    </tr>
                    <tr v-if="!items.length"><td :colspan="widget.columns.length" class="py-6 text-center text-on-surface-variant">{{ widget.empty ?? 'Aucune donnée.' }}</td></tr>
                </tbody>
            </table>
        </div>

        <!-- Alertes -->
        <div v-else-if="widget.type === 'alerts'" class="space-y-2.5">
            <div v-for="(item, i) in items" :key="i" class="flex items-start gap-3 rounded-lg border p-3 text-xs" :class="toneAlert[item.tone] ?? toneAlert.info">
                <Icon :name="item.icon ?? 'info'" class="text-lg" />
                <div>
                    <div class="font-bold">{{ item.title }}</div>
                    <div class="mt-0.5">{{ item.text }}</div>
                </div>
            </div>
            <div v-if="!items.length" class="flex items-center gap-2 py-4 text-xs text-on-surface-variant">
                <Icon name="check_circle" class="text-lg text-secondary" /> {{ widget.empty ?? 'Aucune alerte pour le moment.' }}
            </div>
        </div>

        <!-- Actions rapides -->
        <div v-else-if="widget.type === 'links'" class="grid grid-cols-1 gap-2 sm:grid-cols-2">
            <a v-for="item in items" :key="item.href" :href="item.href" class="flex items-center gap-2.5 rounded-lg border border-surface-container-highest px-3 py-2.5 text-xs font-semibold text-on-surface hover:bg-surface-container-low">
                <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-subtle text-brand"><Icon :name="item.icon ?? 'arrow_forward'" class="text-base" /></span>
                {{ item.label }}
            </a>
        </div>
    </Card>
</template>
