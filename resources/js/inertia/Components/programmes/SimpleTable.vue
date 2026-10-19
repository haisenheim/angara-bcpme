<script setup>
/**
 * Tableau non paginé décrit par le serveur : { columns, rows: [{ cells, href }], empty }.
 * Cellules : texte, { title, subtitle, href } ou { badge, variant }.
 * Les liens sont de simples <a> : la cible peut encore être un écran Blade.
 */
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';

defineProps({
    table: { type: Object, required: true },
});

const alignClass = (col) => (col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : 'text-left');
const isEmpty = (v) => v === null || v === undefined || v === '';
</script>

<template>
    <div class="overflow-x-auto rounded-lg border border-surface-container-highest">
        <table class="w-full text-xs">
            <thead class="border-b border-surface-container-highest bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                <tr>
                    <th v-for="col in table.columns" :key="col.key" class="whitespace-nowrap px-3 py-2 font-semibold" :class="alignClass(col)">{{ col.label }}</th>
                    <th v-if="table.rows.some((r) => r.href)" class="w-12 px-3 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-highest">
                <tr v-for="(row, i) in table.rows" :key="i" class="hover:bg-surface-container-low/50">
                    <td v-for="col in table.columns" :key="col.key" class="px-3 py-2.5 align-middle" :class="alignClass(col)">
                        <template v-for="cell in [row.cells[col.key]]" :key="col.key">
                            <StatusBadge v-if="cell && typeof cell === 'object' && cell.badge" :variant="cell.variant">{{ cell.badge }}</StatusBadge>
                            <div v-else-if="cell && typeof cell === 'object'">
                                <a v-if="cell.href" :href="cell.href" class="font-semibold text-on-surface hover:text-secondary hover:underline">{{ cell.title }}</a>
                                <div v-else class="font-semibold text-on-surface">{{ cell.title }}</div>
                                <div v-if="cell.subtitle" class="text-[11px] text-on-surface-variant">{{ cell.subtitle }}</div>
                            </div>
                            <span v-else :class="isEmpty(cell) ? 'text-on-surface-variant' : 'text-on-surface'">{{ isEmpty(cell) ? '—' : cell }}</span>
                        </template>
                    </td>
                    <td v-if="table.rows.some((r) => r.href)" class="px-3 py-2.5 text-right">
                        <a v-if="row.href" :href="row.href" class="inline-flex items-center rounded-lg p-1 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface" title="Ouvrir">
                            <Icon name="chevron_right" />
                        </a>
                    </td>
                </tr>
                <tr v-if="!table.rows.length">
                    <td :colspan="table.columns.length + 1" class="px-3 py-8 text-center text-on-surface-variant">{{ table.empty }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
