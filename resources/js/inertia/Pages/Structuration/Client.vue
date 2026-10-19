<script setup>
/**
 * Fiche client du chef de filière : fiche entreprise commune (Entreprises/Show, réutilisée telle quelle
 * via ses props) complétée du bloc « Structuration et dossier d'instruction » propre à ce profil.
 */
import { Link } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';
import EntrepriseSheet from '@/Pages/Entreprises/Show.vue';
import InstructionBundleForm from '@/Components/structuration/InstructionBundleForm.vue';

defineProps({
    sheet: { type: Object, required: true },
    workflow: { type: Object, required: true },
    besoinsUrl: { type: String, default: null },
});
</script>

<template>
    <EntrepriseSheet v-bind="sheet" />

    <!-- Structuration et dossier d'instruction (chef de filière) -->
    <Card title="Structuration (chef de filière)">
        <div class="flex flex-wrap items-center gap-3 text-xs">
            <StatusBadge :variant="workflow.structuration.status.variant">{{ workflow.structuration.status.badge }}</StatusBadge>
            <span v-if="workflow.structuration.at" class="text-on-surface-variant">{{ workflow.structuration.at.label }} <span class="font-semibold text-on-surface">{{ workflow.structuration.at.value }}</span></span>
            <span v-if="workflow.structuration.status.detail && !workflow.structuration.rejection" class="text-on-surface-variant">{{ workflow.structuration.status.detail }}</span>
            <span v-if="workflow.structuration.readonlyNotice" class="text-on-surface-variant">• {{ workflow.structuration.readonlyNotice }}</span>
        </div>
        <RejectionBanner v-if="workflow.structuration.rejection" class="mt-3" :rejection="workflow.structuration.rejection" />
        <p class="mt-3 text-xs text-on-surface-variant">
            <template v-if="workflow.structuration.showDetail">Le détail (analyses, besoins, programmes) est présenté dans l'onglet « Structuration » ci-dessus.</template>
            <template v-else>Aucune structuration enregistrée. Utilisez « Structurer » pour démarrer.</template>
            Une structuration par client ; le dossier d'instruction est composé après validation du chef d'agence.
        </p>
    </Card>

    <Card v-if="workflow.dossiers.length" title="Dossiers d'instruction transmis à l'agence">
        <p class="text-xs text-on-surface-variant">Chaque dossier transmis est validé ou rejeté par le chef d'agence. Vous pouvez en soumettre d'autres tant qu'il reste des programmes disponibles.</p>
        <div class="overflow-x-auto">
            <table class="mt-3 w-full text-left text-xs">
                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr><th class="py-2">Programmes</th><th class="py-2">Engagements</th><th class="py-2">Transmis</th><th class="py-2">Statut</th><th class="py-2"></th></tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="d in workflow.dossiers" :key="d.token" class="align-top">
                        <td class="py-2.5 font-semibold text-on-surface">{{ d.programmes }}</td>
                        <td class="py-2.5">
                            <template v-if="d.sollicites || d.enCours">
                                <div>Sollicités : {{ d.sollicites ?? '—' }}</div>
                                <div class="text-on-surface-variant">En cours : {{ d.enCours ?? '—' }}</div>
                            </template>
                            <span v-else>—</span>
                        </td>
                        <td class="py-2.5">{{ d.transmisLe ?? '—' }}<div v-if="d.transmisPar" class="text-on-surface-variant">{{ d.transmisPar }}</div></td>
                        <td class="py-2.5">
                            <StatusBadge :variant="d.statut.variant">{{ d.statut.badge }}</StatusBadge>
                            <p v-if="d.motif" class="mt-1 whitespace-pre-line text-error">{{ d.motif }}</p>
                        </td>
                        <td class="py-2.5 text-right">
                            <Link :href="d.href" class="inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Ouvrir <Icon name="chevron_right" class="text-base" /></Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </Card>

    <div v-if="workflow.composer" id="composition-dossier-instruction" class="scroll-mt-24">
        <InstructionBundleForm :composer="workflow.composer" />
    </div>
    <div v-else-if="workflow.noProgrammeLeft" class="rounded-xl border border-surface-container-highest bg-surface-container-low p-4 text-xs text-on-surface">
        <span class="font-semibold">Dossier d'instruction.</span> Tous les programmes sont déjà rattachés à un dossier pour ce client, ou aucun programme n'est disponible.
    </div>
</template>
