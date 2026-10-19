<script setup>
/**
 * Travail d'instruction en lecture seule (espaces responsables de pôle, analystes, direction, conformité) :
 * montants et budgets, pièces, rubriques de l'analyste financier, avis du chef d'agence, notation et grille.
 */
import { Head } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import AnalyseZones from '@/Components/workflow/AnalyseZones.vue';
import PiecesManager from '@/Components/workflow/PiecesManager.vue';
import GrilleNotation from '@/Components/workflow/GrilleNotation.vue';
import SmeCard from '@/Components/workflow/SmeCard.vue';

defineProps({
    title: String,
    dossier: Object,
    notice: { type: Object, default: null },
    consultation: Object,
    zonesTitle: { type: String, default: null },
    zonesIntro: { type: String, default: null },
    zones: { type: Array, default: () => [] },
    legacy: { type: String, default: null },
    caAvis: { type: String, default: null },
    financier: Object,
    grille: Object,
    pieces: { type: Array, default: () => [] },
    links: { type: Array, default: () => [] },
});
</script>

<template>
    <Head :title="`${title} — ${dossier.reference}`" />
    <PageHeader :title="title" :subtitle="`${dossier.reference} — ${dossier.entreprise ?? 'Entreprise non renseignée'}${dossier.programmes ? ' — ' + dossier.programmes : ''}`">
        <template v-if="links.length" #actions>
            <Button v-for="l in links" :key="l.href" :href="l.href" :icon="l.icon" :target="l.external ? '_blank' : null">{{ l.label }}</Button>
        </template>
    </PageHeader>

    <div v-if="notice" class="rounded-lg border border-surface-container-highest bg-surface-container-low p-3 text-xs text-on-surface">{{ notice.text }}</div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <Card title="Totaux engagements (structuration)">
            <dl class="grid grid-cols-2 gap-3 text-xs">
                <div><dt class="text-on-surface-variant">Total engagements sollicités</dt><dd class="font-bold text-on-surface">{{ consultation.sollicite ?? '—' }}</dd></div>
                <div><dt class="text-on-surface-variant">Total engagements en cours</dt><dd class="font-bold text-on-surface">{{ consultation.enCours ?? '—' }}</dd></div>
            </dl>
        </Card>
        <Card title="Programmes et budgets d'appui">
            <p v-if="!consultation.programmes.length" class="text-xs text-on-surface-variant">Aucune ligne programme budgétisée n’est encore enregistrée pour ce dossier.</p>
            <table v-else class="w-full text-left text-xs">
                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr><th class="py-2">Programme</th><th class="py-2 text-right">Financier</th><th class="py-2 text-right">Non financier</th><th class="py-2 text-right">Total</th></tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="p in consultation.programmes" :key="p.name">
                        <td class="py-2 font-semibold text-on-surface">{{ p.name }}</td>
                        <td class="py-2 text-right">{{ p.financier }}</td>
                        <td class="py-2 text-right">{{ p.nonFinancier }}</td>
                        <td class="py-2 text-right font-semibold">{{ p.total }}</td>
                    </tr>
                </tbody>
                <tfoot v-if="consultation.totaux" class="border-t-2 border-surface-container-highest font-bold text-on-surface">
                    <tr>
                        <td class="py-2">Total</td>
                        <td class="py-2 text-right">{{ consultation.totaux.financier }}</td>
                        <td class="py-2 text-right">{{ consultation.totaux.nonFinancier }}</td>
                        <td class="py-2 text-right">{{ consultation.totaux.general }}</td>
                    </tr>
                </tfoot>
            </table>
        </Card>
    </div>

    <PiecesManager :pieces="pieces" />

    <Card v-if="zonesTitle" :title="zonesTitle">
        <p v-if="zonesIntro" class="mb-3 text-xs text-on-surface-variant">{{ zonesIntro }}</p>
        <AnalyseZones :zones="zones" />
        <div v-if="legacy" class="mt-3 rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3 text-xs">
            <p class="mb-2 font-semibold text-on-surface">Contenu consolidé (synchronisation ou historique)</p>
            <SafeHtml :html="legacy" class="leading-relaxed text-on-surface" />
        </div>
    </Card>

    <Card v-if="caAvis" title="Avis du chef d'agence">
        <p class="mb-2 text-xs text-on-surface-variant">Transmis au responsable exploitation avec le dossier.</p>
        <SafeHtml :html="caAvis" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3 text-xs leading-relaxed text-on-surface" />
    </Card>

    <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-3">
        <SmeCard :sme="financier.sme ?? { noteFinale: null }" title="Données financières & PME">
            <p class="text-on-surface-variant">Import DSF et saisies effectués par l’analyste dans son espace.</p>
            <p class="text-on-surface-variant">Indicateurs financiers enregistrés : <strong class="text-on-surface">{{ financier.indicateurs }}</strong></p>
        </SmeCard>
        <div class="xl:col-span-2">
            <GrilleNotation :grille="grille" />
        </div>
    </div>
</template>
