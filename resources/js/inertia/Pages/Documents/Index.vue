<script setup>
/**
 * Bibliothèque des modèles de documents (consultation par tous les profils connectés).
 * Le téléchargement passe par la route existante document-templates.download.
 */
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';

const props = defineProps({
    templates: { type: Array, default: () => [] },
    manageUrl: { type: String, default: null },
});

const query = ref('');
const norm = (s) => String(s ?? '').normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase();
const filtered = computed(() => {
    const q = norm(query.value.trim());
    return q ? props.templates.filter((t) => norm(`${t.title} ${t.description} ${t.filename}`).includes(q)) : props.templates;
});

const icons = { pdf: 'picture_as_pdf', doc: 'description', docx: 'description', odt: 'description', xls: 'table_view', xlsx: 'table_view', ods: 'table_view', csv: 'table_view', png: 'image', jpg: 'image', jpeg: 'image' };
</script>

<template>
    <Head title="Modèles de documents" />
    <PageHeader title="Modèles de documents" subtitle="Fichiers mis à disposition par l’administration — téléchargement réservé aux utilisateurs connectés.">
        <template v-if="manageUrl" #actions>
            <Button :href="manageUrl" icon="settings">Gérer les modèles</Button>
        </template>
    </PageHeader>

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs">
        <div v-if="templates.length > 5" class="border-b border-surface-container-highest p-4">
            <div class="relative max-w-md">
                <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
                <input v-model="query" type="search" placeholder="Rechercher un modèle…" class="w-full rounded-lg border-surface-container-highest bg-surface-container-low pl-8 text-xs focus:border-secondary focus:ring-secondary">
            </div>
        </div>

        <ul v-if="filtered.length" class="divide-y divide-surface-container-highest">
            <li v-for="t in filtered" :key="t.id" class="flex flex-col gap-3 p-4 md:flex-row md:items-center md:justify-between">
                <div class="flex min-w-0 items-start gap-3">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-brand-subtle text-secondary">
                        <Icon :name="icons[t.extension] ?? 'draft'" />
                    </span>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-on-surface">{{ t.title }}</div>
                        <p v-if="t.description" class="mt-0.5 whitespace-pre-line text-xs text-on-surface-variant">{{ t.description }}</p>
                        <div class="mt-1 text-[11px] text-on-surface-variant">
                            {{ t.filename }}<template v-if="t.size"> · {{ t.size }}</template>
                        </div>
                    </div>
                </div>
                <Button :href="t.download" variant="primary" icon="download" class="shrink-0">Télécharger</Button>
            </li>
        </ul>
        <p v-else class="p-8 text-center text-xs text-on-surface-variant">
            {{ templates.length ? 'Aucun modèle ne correspond à la recherche.' : 'Aucun modèle n’est encore disponible.' }}
        </p>
    </section>
</template>
