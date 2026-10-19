<script setup>
/**
 * Analyse critique d'un dossier d'instruction : grille d'analyse critique (zones historiques, saisie par zone
 * selon le profil), sept zones en lecture, ou dossier d'analyse critique (rubriques de l'analyste financier).
 * Contenu, droits de saisie et actions sont décrits par le serveur (DossierWorkspacePresenter).
 */
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import ActionPanel from '@/Components/workflow/ActionPanel.vue';
import AnalyseZones from '@/Components/workflow/AnalyseZones.vue';
import PiecesManager from '@/Components/workflow/PiecesManager.vue';

defineProps({
    title: String,
    kicker: { type: String, default: null },
    dossier: Object,
    intro: { type: String, default: null },
    notice: { type: Object, default: null },
    meta: { type: Array, default: () => [] },
    budgets: { type: Array, default: () => [] },
    zonesTitle: { type: String, default: null },
    zones: { type: Array, default: () => [] },
    legacy: { type: String, default: null },
    pieces: { type: Array, default: () => [] },
    actions: { type: Array, default: () => [] },
    links: { type: Array, default: () => [] },
});

const noticeClass = {
    warning: 'border-amber-200 bg-amber-50 text-amber-800',
    neutral: 'border-surface-container-highest bg-surface-container-low text-on-surface',
};
</script>

<template>
    <Head :title="`${title} — ${dossier.reference}`" />
    <PageHeader :title="title" :subtitle="`${dossier.reference} — ${dossier.entreprise ?? 'Entreprise non renseignée'}${dossier.programmes ? ' — ' + dossier.programmes : ''}`">
        <template v-if="links.length" #actions>
            <Button v-for="l in links" :key="l.href" :href="l.href" :icon="l.icon" :target="l.external ? '_blank' : null">{{ l.label }}</Button>
        </template>
    </PageHeader>

    <p v-if="intro" class="text-xs text-on-surface-variant">{{ intro }}</p>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="space-y-5" :class="actions.length ? 'lg:col-span-8' : 'lg:col-span-12'">
            <div v-if="notice" class="rounded-lg border p-3 text-xs" :class="noticeClass[notice.variant] ?? noticeClass.neutral">{{ notice.text }}</div>

            <div v-if="meta.length" class="grid grid-cols-1 gap-3.5 md:grid-cols-2">
                <div v-for="m in meta" :key="m.label" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
                    <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ m.label }}</div>
                    <div class="text-sm font-bold text-on-surface">{{ m.value }}</div>
                    <div v-if="m.sub" class="text-[11px] text-on-surface-variant">{{ m.sub }}</div>
                </div>
            </div>

            <Card v-if="budgets.length" title="Programmes et budgets d'appui">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                        <tr><th class="py-2">Programme</th><th class="py-2 text-right">Appui financier</th><th class="py-2 text-right">Appui non financier</th></tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-highest">
                        <tr v-for="b in budgets" :key="b.name">
                            <td class="py-2 font-semibold text-on-surface">{{ b.name }}</td>
                            <td class="py-2 text-right">{{ b.financier ?? '—' }}</td>
                            <td class="py-2 text-right">{{ b.nonFinancier ?? '—' }}</td>
                        </tr>
                    </tbody>
                </table>
            </Card>

            <Card :title="zonesTitle ?? kicker ?? title">
                <AnalyseZones :zones="zones" />
                <div v-if="legacy" class="mt-3 rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3 text-xs">
                    <p class="mb-2 font-semibold text-on-surface">Contenu consolidé (synchronisation ou historique)</p>
                    <SafeHtml :html="legacy" class="leading-relaxed text-on-surface" />
                </div>
            </Card>

            <PiecesManager :pieces="pieces" />
        </div>

        <aside v-if="actions.length" class="sticky top-20 lg:col-span-4">
            <ActionPanel :actions="actions" />
        </aside>
    </div>
</template>
