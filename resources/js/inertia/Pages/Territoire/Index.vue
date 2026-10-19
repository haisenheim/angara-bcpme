<script setup>
/**
 * Organisation du territoire : arborescence région → département → arrondissement → village,
 * dépliable, avec recherche locale (le référentiel est léger et transmis en une fois).
 */
import { computed, reactive, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    tree: { type: Array, default: () => [] },
    totals: { type: Array, default: () => [] },
});

const open = reactive({});
const query = ref('');

const norm = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();

// Filtre : on garde un nœud s'il correspond ou si l'un de ses descendants correspond.
const filterNodes = (nodes, q) => nodes
    .map((n) => {
        const children = filterNodes(n.children ?? [], q);
        return norm(n.name).includes(q) || children.length ? { ...n, children: norm(n.name).includes(q) ? n.children : children } : null;
    })
    .filter(Boolean);

const visibleTree = computed(() => (query.value.trim() ? filterNodes(props.tree, norm(query.value.trim())) : props.tree));
const isOpen = (n) => (query.value.trim() ? true : !!open[n.id]);
const toggle = (n) => (open[n.id] = !open[n.id]);

// Liste aplatie des lignes visibles (profondeur pour l'indentation).
const rows = computed(() => {
    const out = [];
    const walk = (nodes, depth) => nodes.forEach((n) => {
        out.push({ node: n, depth });
        if ((n.children ?? []).length && isOpen(n)) walk(n.children, depth + 1);
    });
    walk(visibleTree.value, 0);
    return out;
});

const expandAll = (value) => {
    const walk = (nodes) => nodes.forEach((n) => {
        if ((n.children ?? []).length) {
            open[n.id] = value;
            walk(n.children);
        }
    });
    walk(props.tree);
};

const levelClass = {
    'Région': 'font-bold text-on-surface',
    'Département': 'font-semibold text-on-surface',
    'Arrondissement': 'text-on-surface',
    'Village': 'text-on-surface-variant',
};
</script>

<template>
    <Head title="Organisation du territoire" />
    <PageHeader title="Organisation du territoire" subtitle="Liste de toutes les circonscriptions : régions, départements, arrondissements et villages." />

    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
        <div v-for="t in totals" :key="t.label" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ t.label }}</span>
            <div class="mt-1 text-2xl font-bold tracking-tight text-on-surface">{{ t.value }}</div>
        </div>
    </div>

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs">
        <div class="flex flex-wrap items-end gap-3 border-b border-surface-container-highest p-4">
            <div class="flex min-w-64 flex-1 flex-col gap-1">
                <label for="terr-q" class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">Recherche</label>
                <div class="relative">
                    <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
                    <input id="terr-q" v-model="query" type="search" placeholder="Région, département, arrondissement, village…" class="w-full rounded-lg border-surface-container-highest bg-surface-container-low pl-8 text-xs focus:border-secondary focus:ring-secondary">
                </div>
            </div>
            <div class="ml-auto flex gap-2">
                <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low" @click="expandAll(true)">
                    <Icon name="unfold_more" class="text-base" /> Tout déplier
                </button>
                <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low" @click="expandAll(false)">
                    <Icon name="unfold_less" class="text-base" /> Tout replier
                </button>
            </div>
        </div>

        <table class="w-full text-xs">
            <thead class="border-b border-surface-container-highest bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                <tr>
                    <th class="px-4 py-2.5 text-left font-semibold">Nom</th>
                    <th class="w-40 px-4 py-2.5 text-left font-semibold">Niveau</th>
                    <th class="w-40 px-4 py-2.5 text-right font-semibold">Circonscriptions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container-highest">
                <tr v-for="{ node, depth } in rows" :key="node.id" class="hover:bg-surface-container-low/50">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-1" :style="{ paddingLeft: `${depth * 1.25}rem` }">
                            <button v-if="node.children?.length" type="button" class="rounded p-0.5 text-on-surface-variant hover:bg-surface-container-high" :aria-expanded="isOpen(node)" @click="toggle(node)">
                                <Icon :name="isOpen(node) ? 'expand_more' : 'chevron_right'" class="text-base" />
                            </button>
                            <span v-else class="inline-block w-5"></span>
                            <span :class="levelClass[node.level]">{{ node.name }}</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 text-on-surface-variant">{{ node.level }}</td>
                    <td class="px-4 py-2 text-right text-on-surface">{{ node.children?.length || '—' }}</td>
                </tr>
                <tr v-if="!rows.length">
                    <td colspan="3" class="px-4 py-10 text-center text-on-surface-variant">Aucune localité trouvée.</td>
                </tr>
            </tbody>
        </table>
    </section>
</template>
