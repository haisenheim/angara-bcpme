<script setup>
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import WorkflowStepper from '@/Components/workflow/WorkflowStepper.vue';
import PoleCard from '@/Components/workflow/PoleCard.vue';
import ActionPanel from '@/Components/workflow/ActionPanel.vue';
import Timeline from '@/Components/workflow/Timeline.vue';
import PiecesManager from '@/Components/workflow/PiecesManager.vue';

const props = defineProps({
    space: Object,
    readonly: Boolean,
    dossier: Object,
    steps: Array,
    poles: Array,
    timeline: Array,
    pieces: Array,
    delegation: Object,
    actions: Array,
    links: Object,
    fichierTypes: { type: Array, default: () => [] },
});

const tabs = computed(() => [
    props.poles.length ? { key: 'avis', label: 'Avis des pôles', count: props.poles.filter((p) => p.state === 'done').length + '/' + props.poles.length } : null,
    { key: 'synthese', label: 'Synthèse' },
    { key: 'programmes', label: 'Programmes & budgets', count: props.dossier.programmes.length || null },
    { key: 'pieces', label: 'Pièces justificatives', count: props.pieces.length || null },
    { key: 'historique', label: 'Historique' },
].filter(Boolean));

const tab = ref(tabs.value[0].key);

const currentIndex = computed(() => {
    const i = props.steps.findIndex((s) => s.state === 'current' || s.state === 'rejected');
    return i === -1 ? props.steps.length : i + 1;
});

const metrics = computed(() => [
    { label: 'Agence', value: props.dossier.agence ?? '—', sub: props.dossier.gestionnaire ? `Gest. : ${props.dossier.gestionnaire}` : null },
    { label: 'Engagements sollicités', value: props.dossier.montants.sollicite ?? '—', sub: null },
    { label: 'Engagements en cours', value: props.dossier.montants.enCours ?? '—', sub: null },
    { label: 'Programme(s) d\'appui', value: props.dossier.programmesLabel || '—', sub: null },
    { label: 'Analyste financier', value: props.dossier.analysteFinancier ?? 'Non affecté', sub: null },
    { label: 'Étape courante', value: props.dossier.currentStep || '—', sub: props.dossier.createdAt ? `Créé le ${props.dossier.createdAt}` : null, highlight: true },
]);

const toolLinks = computed(() => [
    ['Grille de notation & instruction', 'table_view', props.links.instruction],
    ['Synthèse d\'analyse critique', 'summarize', props.links.analyseCritique],
    ['Fiche entreprise & grille des engagements', 'storefront', props.links.entreprise],
].filter(([, , href]) => href));
</script>

<template>
    <Head :title="`${dossier.reference} — ${dossier.entreprise?.name ?? 'Dossier'}`" />

    <!-- En-tête & indicateurs clés -->
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-6 shadow-xs">
        <div class="flex flex-col justify-between gap-4 border-b border-surface-container-highest pb-5 lg:flex-row lg:items-center">
            <div>
                <div class="mb-1 flex flex-wrap items-center gap-3">
                    <h1 class="text-xl font-bold tracking-tight text-on-surface">
                        Dossier d'instruction {{ dossier.reference }} — {{ dossier.entreprise?.name ?? 'Entreprise non renseignée' }}
                    </h1>
                    <StatusBadge :variant="dossier.statut.variant" :pulse="dossier.statut.code === 'en_cours'">
                        {{ dossier.statut.label }}<template v-if="dossier.statut.detail"> — {{ dossier.statut.detail }}</template>
                    </StatusBadge>
                </div>
                <p v-if="dossier.entreprise" class="flex flex-wrap items-center gap-2 text-xs text-on-surface-variant">
                    <span v-if="dossier.entreprise.rccm">RCCM : {{ dossier.entreprise.rccm }}</span>
                    <span v-if="dossier.entreprise.phone">• Téléphone : {{ dossier.entreprise.phone }}</span>
                    <span v-if="dossier.entreprise.manager">• Dirigeant : {{ dossier.entreprise.manager }}</span>
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap items-center gap-2.5">
                <Button v-if="links.instructionPdf" :href="links.instructionPdf" icon="picture_as_pdf">Fiche d'instruction PDF</Button>
                <Button v-if="links.analyseCritiquePdf" :href="links.analyseCritiquePdf" icon="description">Synthèse PDF</Button>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3.5 pt-5 md:grid-cols-3 xl:grid-cols-6">
            <div
                v-for="m in metrics"
                :key="m.label"
                class="rounded-lg border p-3.5"
                :class="m.highlight ? 'border-secondary/30 bg-brand-subtle/60' : 'border-surface-container-highest bg-surface-container-low/60'"
            >
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ m.label }}</div>
                <div class="text-xs font-bold text-on-surface">{{ m.value }}</div>
                <div v-if="m.sub" class="mt-0.5 text-[11px] text-on-surface-variant">{{ m.sub }}</div>
            </div>
        </div>
    </section>

    <WorkflowStepper :steps="steps" />

    <!-- Onglets -->
    <nav class="flex items-center gap-1 overflow-x-auto border-b border-surface-container-highest text-xs font-medium">
        <button
            v-for="t in tabs"
            :key="t.key"
            type="button"
            class="whitespace-nowrap border-b-2 px-4 py-2.5 transition-colors"
            :class="tab === t.key ? 'rounded-t-md border-primary bg-surface-container-lowest font-bold text-on-surface' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
            @click="tab = t.key"
        >
            {{ t.label }}
            <span v-if="t.count" class="ml-1.5 rounded-full bg-secondary/15 px-1.5 text-[10px] font-bold text-secondary">{{ t.count }}</span>
        </button>
        <a
            v-for="[label, icon, href] in toolLinks"
            :key="href"
            :href="href"
            class="flex items-center gap-1 whitespace-nowrap border-b-2 border-transparent px-4 py-2.5 text-on-surface-variant hover:text-on-surface"
            
        >
            {{ label }} <Icon name="open_in_new" class="text-sm" />
        </a>
    </nav>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <!-- Contenu principal -->
        <div class="space-y-5 lg:col-span-8">
            <template v-if="tab === 'avis'">
                <PoleCard v-for="pole in poles" :key="pole.key" :pole="pole" />
            </template>

            <Card v-else-if="tab === 'synthese'" title="Synthèse du dossier">
                <dl class="grid grid-cols-1 gap-x-8 gap-y-3 text-xs md:grid-cols-2">
                    <div><dt class="text-on-surface-variant">Entreprise</dt><dd class="font-semibold text-on-surface">{{ dossier.entreprise?.name ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">E-mail</dt><dd class="font-semibold text-on-surface">{{ dossier.entreprise?.email ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Agence</dt><dd class="font-semibold text-on-surface">{{ dossier.agence ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Gestionnaire</dt><dd class="font-semibold text-on-surface">{{ dossier.gestionnaire ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Total des engagements sollicités</dt><dd class="font-semibold text-on-surface">{{ dossier.montants.sollicite ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Total des engagements en cours</dt><dd class="font-semibold text-on-surface">{{ dossier.montants.enCours ?? '—' }}</dd></div>
                    <div><dt class="text-on-surface-variant">Statut</dt><dd class="font-semibold text-on-surface">{{ dossier.statut.label }}</dd></div>
                    <div><dt class="text-on-surface-variant">Étape courante</dt><dd class="font-semibold text-on-surface">{{ dossier.currentStep || '—' }}</dd></div>
                </dl>
            </Card>

            <Card v-else-if="tab === 'programmes'" title="Programmes d'appui et budgets" :padded="false">
                <div class="px-5 pb-5">
                    <table class="w-full text-left text-xs">
                        <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                            <tr><th class="py-2">Programme</th><th class="py-2 text-right">Appui financier</th><th class="py-2 text-right">Appui non financier</th></tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-highest">
                            <tr v-for="p in dossier.programmes" :key="p.name">
                                <td class="py-2.5 font-semibold text-on-surface">{{ p.name }}</td>
                                <td class="py-2.5 text-right">{{ p.financier ?? '—' }}</td>
                                <td class="py-2.5 text-right">{{ p.nonFinancier ?? '—' }}</td>
                            </tr>
                            <tr v-if="!dossier.programmes.length"><td colspan="3" class="py-4 text-center text-on-surface-variant">Aucun programme rattaché.</td></tr>
                        </tbody>
                    </table>
                </div>
            </Card>

            <PiecesManager v-else-if="tab === 'pieces'" :pieces="pieces" :types="fichierTypes" :store-url="links.piecesStore" />

            <Card v-else title="Historique complet">
                <Timeline :events="timeline" />
            </Card>
        </div>

        <!-- Colonne d'actions (sticky) -->
        <aside class="sticky top-20 space-y-5 lg:col-span-4">
            <ActionPanel
                v-if="!readonly"
                :actions="actions"
                :delegation="delegation"
                :step-label="`Étape ${Math.min(currentIndex, steps.length)} / ${steps.length}`"
            />
            <Card title="Derniers événements">
                <Timeline :events="timeline.slice(0, 5)" />
            </Card>
        </aside>
    </div>
</template>
