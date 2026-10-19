<script setup>
/**
 * Décision du chef d'agence sur un dossier d'instruction multi-programmes soumis par le chef de filière :
 * totaux d'engagements, budgets par programme, historique, pièces et actions de validation / rejet.
 * Les actions (et leur disponibilité) sont décrites par le serveur (AgenceValidationPresenter).
 */
import { computed, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';

const props = defineProps({
    dossier: Object,
    engagements: Object,
    budgets: Object,
    timeline: Array,
    pieces: Array,
    decision: Object,
    actions: { type: Array, default: () => [] },
    links: { type: Array, default: () => [] },
});

const opened = ref(null);
const main = computed(() => props.actions.filter((a) => a.variant !== 'danger'));
const rejects = computed(() => props.actions.filter((a) => a.variant === 'danger'));

const metrics = computed(() => [
    { label: 'Agence', value: props.dossier.agence ?? '—' },
    { label: 'Transmis le', value: props.dossier.submittedAt ?? '—', sub: props.dossier.submittedBy ? `par ${props.dossier.submittedBy}` : null },
    { label: 'Total engagements sollicités', value: props.engagements.sollicites ?? '—', highlight: true },
    { label: 'Total engagements en cours', value: props.engagements.enCours ?? '—' },
]);

const kindLabel = (kind) => ({
    analyse_critique: 'Avis associé',
    creation: 'Création',
    validation: 'Validation',
    transmission: 'Transmission',
    affectation: 'Affectation',
    avis: 'Avis',
}[kind] ?? 'Événement');
</script>

<template>
    <Head :title="`Validation — ${dossier.entreprise?.name ?? dossier.reference}`" />

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-6 shadow-xs">
        <div class="flex flex-col justify-between gap-4 border-b border-surface-container-highest pb-5 lg:flex-row lg:items-center">
            <div>
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-bold tracking-tight text-on-surface">{{ dossier.entreprise?.name ?? 'Client' }}</h1>
                    <StatusBadge>{{ dossier.reference }}</StatusBadge>
                    <StatusBadge :variant="dossier.statut.variant">{{ dossier.statut.badge }}</StatusBadge>
                </div>
                <p class="text-xs text-on-surface-variant">
                    Structuration soumise par le chef de filière — à valider ou rejeter par le chef d'agence.
                    <template v-if="dossier.programmesLabel"> Programmes : {{ dossier.programmesLabel }}.</template>
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap items-center gap-2.5">
                <Button v-for="l in links" :key="l.href" :href="l.href" :icon="l.icon">{{ l.label }}</Button>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3.5 pt-5 xl:grid-cols-4">
            <div
                v-for="m in metrics" :key="m.label"
                class="rounded-lg border p-3.5"
                :class="m.highlight ? 'border-secondary/30 bg-brand-subtle/60' : 'border-surface-container-highest bg-surface-container-low/60'"
            >
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ m.label }}</div>
                <div class="text-xs font-bold text-on-surface">{{ m.value }}</div>
                <div v-if="m.sub" class="mt-0.5 text-[11px] text-on-surface-variant">{{ m.sub }}</div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-12">
        <div class="space-y-5 lg:col-span-8">
            <Card title="Programmes et budgets d'appui">
                <div>
                    <template v-if="budgets.lignes.length">
                        <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div v-for="[label, value] in [['Total appui financier', budgets.totaux.financier], ['Total appui non financier', budgets.totaux.nonFinancier], ['Total général', budgets.totaux.general]]" :key="label" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3">
                                <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ label }}</div>
                                <div class="text-sm font-bold text-on-surface">{{ value }}</div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                                    <tr>
                                        <th class="py-2">Programme</th>
                                        <th class="py-2 text-right">Appui financier</th>
                                        <th class="py-2 text-right">Appui non financier</th>
                                        <th class="py-2 text-right">Total programme</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-surface-container-highest">
                                    <tr v-for="l in budgets.lignes" :key="l.programme">
                                        <td class="py-2.5 font-semibold text-on-surface">{{ l.programme }}</td>
                                        <td class="py-2.5 text-right">{{ l.financier }}</td>
                                        <td class="py-2.5 text-right">{{ l.nonFinancier }}</td>
                                        <td class="py-2.5 text-right font-semibold">{{ l.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>
                    <p v-else class="text-xs text-on-surface-variant">Aucune ligne programme budgétisée n'est encore enregistrée pour ce dossier.</p>
                </div>
            </Card>

            <Card title="Pièces jointes">
                <ul v-if="pieces.length" class="divide-y divide-surface-container-highest text-xs">
                    <li v-for="p in pieces" :key="p.id" class="flex flex-wrap items-center justify-between gap-2 py-2.5">
                        <div class="min-w-0">
                            <div class="font-semibold text-on-surface">{{ p.name }}</div>
                            <div class="text-[11px] text-on-surface-variant">{{ p.type ?? 'Type non renseigné' }}<template v-if="p.uploadedBy"> • {{ p.uploadedBy }}</template><template v-if="p.uploadedAt"> • {{ p.uploadedAt }}</template></div>
                        </div>
                        <a v-if="p.url" :href="p.url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Consulter <Icon name="open_in_new" class="text-sm" /></a>
                    </li>
                </ul>
                <p v-else class="text-xs text-on-surface-variant">Aucune pièce jointe.</p>
            </Card>

            <Card title="Historique du dossier">
                <ol v-if="timeline.length" class="space-y-4 text-xs">
                    <li v-for="(e, i) in timeline" :key="i" class="border-b border-surface-container-highest pb-4 last:border-0 last:pb-0">
                        <div class="mb-1 flex flex-wrap items-center gap-2">
                            <StatusBadge :variant="e.kind === 'analyse_critique' ? 'info' : 'neutral'">{{ kindLabel(e.kind) }}</StatusBadge>
                            <span v-if="e.source" class="text-[11px] text-on-surface-variant">{{ e.source }}</span>
                            <time v-if="e.at" class="text-[11px] text-on-surface-variant">{{ e.at }}</time>
                        </div>
                        <div class="font-semibold text-on-surface">{{ e.label }}</div>
                        <p class="text-[11px] text-on-surface-variant">{{ e.actor ? `${e.actor}${e.actorRole ? ' — ' + e.actorRole : ''}` : 'Auteur non renseigné ou non applicable.' }}</p>
                        <SafeHtml v-if="e.html" :html="e.html" class="mt-2 rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3 leading-relaxed text-on-surface" />
                    </li>
                </ol>
                <p v-else class="text-xs text-on-surface-variant">Aucun événement tracé pour ce dossier.</p>
            </Card>
        </div>

        <aside class="sticky top-20 space-y-5 lg:col-span-4">
            <section class="space-y-4 rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
                <div class="border-b border-surface-container-highest pb-3">
                    <h2 class="text-sm font-bold text-on-surface">Décision agence (structuration)</h2>
                    <p class="mt-0.5 text-[11px] text-on-surface-variant">Décision du chef d'agence de l'agence du dossier.</p>
                </div>
                <StatusBadge :variant="decision.validated ? 'success' : decision.rejected ? 'danger' : decision.pending ? 'warning' : 'neutral'">{{ decision.status }}</StatusBadge>

                <div v-if="decision.validated" class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-900">
                    Validée le {{ decision.validated.at }}<template v-if="decision.validated.by"> par {{ decision.validated.by }}</template>.
                    <p v-if="decision.validated.note" class="mt-1 whitespace-pre-line text-on-surface">{{ decision.validated.note }}</p>
                </div>
                <RejectionBanner v-if="decision.rejected" :rejection="decision.rejected" />
                <p v-if="decision.message" class="text-xs text-on-surface-variant">{{ decision.message }}</p>

                <div v-if="actions.length" class="space-y-2.5">
                    <Button v-for="a in main" :key="a.key" block :variant="a.variant" :icon="a.icon" @click="opened = a">{{ a.label }}</Button>
                    <div v-if="rejects.length" class="space-y-2 rounded-lg border border-error-container bg-error-container/30 p-3">
                        <Button v-for="a in rejects" :key="a.key" block variant="ghost-danger" :icon="a.icon" @click="opened = a">{{ a.label }}</Button>
                    </div>
                </div>
                <p v-else-if="!decision.pending" class="text-xs text-on-surface-variant">Aucune décision attendue sur ce dossier.</p>
            </section>
        </aside>
    </div>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
</template>
