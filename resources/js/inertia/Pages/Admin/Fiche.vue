<script setup>
/**
 * Fiche de consultation générique (agence, établissement, organisme, secteur, utilisateur).
 * Sections : liste de propriétés (`items`), liste de liens (`links`) ou tableau (`table`).
 * Actions (modifier, activer, supprimer) décrites par le contrôleur.
 */
import { Head, Link } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import ActionButtons from '@/Components/admin/ActionButtons.vue';

defineProps({
    title: String,
    subtitle: { type: String, default: null },
    badge: { type: Object, default: null },
    sections: { type: Array, default: () => [] },
    actions: { type: Array, default: () => [] },
    dangerActions: { type: Array, default: () => [] },
});

const spanClass = (s) => ({ 1: 'lg:col-span-1', 2: 'lg:col-span-2', 3: 'lg:col-span-3' }[s.span ?? 3]);
</script>

<template>
    <Head :title="title" />
    <PageHeader :title="title" :subtitle="subtitle">
        <template v-if="actions.length || badge" #actions>
            <StatusBadge v-if="badge" :variant="badge.variant">{{ badge.badge }}</StatusBadge>
            <ActionButtons :actions="actions" />
        </template>
    </PageHeader>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <Card v-for="section in sections" :key="section.title" :title="section.title" :class="spanClass(section)" :padded="!section.table">
            <dl v-if="section.items" class="grid grid-cols-1 gap-x-6 gap-y-3 text-xs sm:grid-cols-[minmax(8rem,auto)_1fr]">
                <template v-for="item in section.items" :key="item.label">
                    <dt class="font-semibold text-on-surface-variant">{{ item.label }}</dt>
                    <dd class="text-on-surface" :class="item.multiline ? 'whitespace-pre-line' : ''">
                        <StatusBadge v-if="item.badge" :variant="item.badge.variant">{{ item.badge.badge }}</StatusBadge>
                        <Link v-else-if="item.href" :href="item.href" class="font-semibold text-secondary hover:underline">{{ item.value }}</Link>
                        <template v-else>{{ item.value ?? '—' }}</template>
                    </dd>
                </template>
            </dl>

            <ul v-if="section.links" class="divide-y divide-surface-container-highest text-xs">
                <li v-for="l in section.links" :key="l.href" class="py-2">
                    <Link :href="l.href" class="font-semibold text-secondary hover:underline">{{ l.label }}</Link>
                </li>
                <li v-if="!section.links.length" class="py-2 text-on-surface-variant">{{ section.empty ?? 'Aucun élément.' }}</li>
            </ul>

            <template v-if="section.table">
                <h2 class="px-5 pt-5 text-xs font-semibold uppercase tracking-wider text-on-surface-variant">{{ section.title }}</h2>
                <div class="mt-3 overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead class="border-y border-surface-container-highest bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                            <tr><th v-for="c in section.table.columns" :key="c.key" class="px-4 py-2 text-left font-semibold">{{ c.label }}</th></tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-highest">
                            <tr v-for="(r, i) in section.table.rows" :key="i">
                                <td v-for="c in section.table.columns" :key="c.key" class="px-4 py-2.5 text-on-surface">{{ r[c.key] ?? '—' }}</td>
                            </tr>
                            <tr v-if="!section.table.rows.length">
                                <td :colspan="section.table.columns.length" class="px-4 py-8 text-center text-on-surface-variant">{{ section.empty ?? 'Aucun élément.' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </Card>
    </div>

    <div v-if="dangerActions.length" class="flex flex-wrap gap-2 rounded-xl border border-error/20 bg-error-container/30 p-4">
        <ActionButtons :actions="dangerActions" />
    </div>
</template>
