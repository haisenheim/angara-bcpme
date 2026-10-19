<script setup>
/**
 * Tableau générique paginé côté serveur (contrat : App\Support\Inertia\DataTable).
 * Les filtres sont envoyés en query string ; la pagination suit les liens du serveur.
 */
import { reactive, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';

const props = defineProps({
    table: { type: Object, required: true },
    countLabel: { type: String, default: 'élément(s)' },
});

const values = reactive(Object.fromEntries(props.table.filters.map((f) => [f.name, f.value ?? ''])));
const exportsOpen = ref(false);
let timer = null;

const apply = () => {
    const query = Object.fromEntries(Object.entries(values).filter(([, v]) => v !== '' && v !== null));
    router.get(props.table.baseUrl, query, { preserveState: true, preserveScroll: true, replace: true });
};
const applyDebounced = () => {
    clearTimeout(timer);
    timer = setTimeout(apply, 350);
};
const reset = () => {
    Object.keys(values).forEach((k) => (values[k] = ''));
    apply();
};
const hasActiveFilter = () => Object.values(values).some((v) => v !== '' && v !== null);

const alignClass = (col) => (col.align === 'right' ? 'text-right' : col.align === 'center' ? 'text-center' : 'text-left');
const inputClass = 'rounded-lg border-surface-container-highest bg-surface-container-low text-xs focus:border-secondary focus:ring-secondary';
</script>

<template>
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs">
        <!-- Filtres -->
        <div class="flex flex-wrap items-end gap-3 border-b border-surface-container-highest p-4">
            <div v-for="filter in table.filters" :key="filter.name" class="flex flex-col gap-1" :class="filter.type === 'search' ? 'min-w-64 flex-1' : ''">
                <label :for="`flt-${filter.name}`" class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ filter.label }}</label>
                <div v-if="filter.type === 'search'" class="relative">
                    <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
                    <input :id="`flt-${filter.name}`" v-model="values[filter.name]" type="search" :placeholder="filter.placeholder" class="w-full pl-8" :class="inputClass" @input="applyDebounced">
                </div>
                <select v-else-if="filter.type === 'select'" :id="`flt-${filter.name}`" v-model="values[filter.name]" class="min-w-44" :class="inputClass" @change="apply">
                    <option value="">{{ filter.allLabel }}</option>
                    <option v-for="opt in filter.options" :key="opt.value" :value="opt.value">{{ opt.label }}</option>
                </select>
                <input v-else-if="filter.type === 'date'" :id="`flt-${filter.name}`" v-model="values[filter.name]" type="date" :class="inputClass" @change="apply">
            </div>

            <div class="ml-auto flex items-center gap-2">
                <button v-if="hasActiveFilter()" type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low" @click="reset">
                    <Icon name="restart_alt" class="text-base" /> Réinitialiser
                </button>
                <div v-if="table.exports.length" class="relative">
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-brand px-3 py-2 text-xs font-semibold text-white hover:bg-brand-hover" @click="exportsOpen = !exportsOpen">
                        <Icon name="download" class="text-base" /> Exporter <Icon name="expand_more" class="text-base" />
                    </button>
                    <div v-if="exportsOpen" class="absolute right-0 z-20 mt-1 w-48 rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg" @mouseleave="exportsOpen = false">
                        <a v-for="exp in table.exports" :key="exp.url" :href="exp.url" class="flex items-center gap-2 px-3 py-2 text-xs text-on-surface hover:bg-surface-container-low">
                            <Icon :name="exp.icon" class="text-base text-on-surface-variant" /> {{ exp.label }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="border-b border-surface-container-highest bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr>
                        <th v-for="col in table.columns" :key="col.key" class="px-4 py-2.5 font-semibold" :class="alignClass(col)" :style="col.width ? { width: col.width } : null">{{ col.label }}</th>
                        <th class="w-16 px-4 py-2.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="(row, i) in table.rows" :key="i" class="transition-colors hover:bg-surface-container-low/50" :class="row.href ? 'cursor-pointer' : ''" @click="row.href && router.visit(row.href)">
                        <td v-for="col in table.columns" :key="col.key" class="px-4 py-3 align-middle" :class="alignClass(col)">
                            <template v-for="cell in [row.cells[col.key]]" :key="col.key">
                                <StatusBadge v-if="cell && typeof cell === 'object' && cell.badge" :variant="cell.variant">{{ cell.badge }}</StatusBadge>
                                <div v-else-if="cell && typeof cell === 'object'">
                                    <div class="font-semibold text-on-surface">{{ cell.title }}</div>
                                    <div v-if="cell.subtitle" class="text-[11px] text-on-surface-variant">{{ cell.subtitle }}</div>
                                </div>
                                <span v-else :class="cell === null || cell === undefined || cell === '' ? 'text-on-surface-variant' : 'text-on-surface'">{{ cell ?? '—' }}</span>
                            </template>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <Link v-if="row.href" :href="row.href" class="inline-flex items-center rounded-lg p-1.5 text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface" title="Ouvrir" @click.stop>
                                <Icon name="chevron_right" />
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="!table.rows.length">
                        <td :colspan="table.columns.length + 1" class="px-4 py-10 text-center text-on-surface-variant">{{ table.empty }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-surface-container-highest px-4 py-3 text-xs text-on-surface-variant">
            <span v-if="table.pagination.total">{{ table.pagination.from }}–{{ table.pagination.to }} sur {{ table.pagination.total }} {{ countLabel }}</span>
            <span v-else>0 {{ countLabel }}</span>
            <nav v-if="table.pagination.lastPage > 1" class="flex items-center gap-1" aria-label="Pagination">
                <Link :href="table.pagination.prev ?? '#'" preserve-scroll class="rounded-lg p-1.5 hover:bg-surface-container-low" :class="{ 'pointer-events-none opacity-40': !table.pagination.prev }">
                    <Icon name="chevron_left" />
                </Link>
                <Link
                    v-for="p in table.pagination.pages"
                    :key="p.page"
                    :href="p.url"
                    preserve-scroll
                    class="min-w-8 rounded-lg px-2 py-1 text-center font-semibold"
                    :class="p.active ? 'bg-primary-container text-white' : 'hover:bg-surface-container-low'"
                >{{ p.page }}</Link>
                <Link :href="table.pagination.next ?? '#'" preserve-scroll class="rounded-lg p-1.5 hover:bg-surface-container-low" :class="{ 'pointer-events-none opacity-40': !table.pagination.next }">
                    <Icon name="chevron_right" />
                </Link>
            </nav>
        </div>
    </section>
</template>
