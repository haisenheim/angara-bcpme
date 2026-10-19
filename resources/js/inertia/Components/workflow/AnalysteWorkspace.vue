<script setup>
/**
 * Espace de travail de l'analyste financier sur la fiche dossier : import DSF, notation PME, grille de notation,
 * montants et budgets, sept rubriques d'analyse, simulations. Tout est décrit par le serveur (prop `workspace`).
 */
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';
import GrilleNotation from '@/Components/workflow/GrilleNotation.vue';
import SmeCard from '@/Components/workflow/SmeCard.vue';
import BudgetsEngagementsForm from '@/Components/workflow/BudgetsEngagementsForm.vue';
import AnalyseSectionsForm from '@/Components/workflow/AnalyseSectionsForm.vue';
import SimulationsPanel from '@/Components/workflow/SimulationsPanel.vue';

const props = defineProps({
    workspace: { type: Object, required: true },
});

const dsf = useForm({ dossier_id: props.workspace.dsf.dossierId, annee: '', upload: null });
const submitDsf = () => dsf.post(props.workspace.dsf.url, {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => dsf.reset('annee', 'upload'),
});

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <div class="space-y-5">
        <div v-if="workspace.rejection" class="space-y-1">
            <RejectionBanner :rejection="workspace.rejection" />
            <p class="pl-1 text-[11px] text-on-surface-variant">{{ workspace.rejection.hint }}</p>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            <div class="space-y-5">
                <Card title="Import DSF">
                    <form class="space-y-3 text-xs" @submit.prevent="submitDsf">
                        <div class="space-y-1">
                            <label for="dsf-annee" class="block font-semibold text-on-surface">Année N <span class="text-error">*</span></label>
                            <input id="dsf-annee" v-model="dsf.annee" type="number" min="2000" max="2100" placeholder="Ex. : 2024" required :class="inputClass">
                        </div>
                        <div class="space-y-1">
                            <label for="dsf-file" class="block font-semibold text-on-surface">Fichier DSF</label>
                            <input id="dsf-file" type="file" accept=".xlsx,.xls,.csv" class="block w-full text-xs file:mr-3 file:rounded-lg file:border-0 file:bg-surface-container file:px-3 file:py-2 file:text-xs file:font-semibold" @input="dsf.upload = $event.target.files[0]">
                        </div>
                        <p v-for="(m, k) in dsf.errors" :key="k" class="text-error">{{ m }}</p>
                        <Button type="submit" variant="primary" block icon="upload" :disabled="dsf.processing || !dsf.annee">Enregistrer</Button>
                    </form>
                </Card>
                <SmeCard :sme="workspace.sme" />
                <Card v-if="workspace.engagementGridUrl" title="Engagements">
                    <a :href="workspace.engagementGridUrl" class="inline-flex items-center gap-1.5 text-xs font-semibold text-secondary hover:underline">
                        <Icon name="table_view" class="text-base" /> Ouvrir la grille des engagements
                    </a>
                </Card>
            </div>
            <div class="xl:col-span-2">
                <GrilleNotation :grille="workspace.grille" />
            </div>
        </div>

        <BudgetsEngagementsForm v-if="workspace.budgets" :budgets="workspace.budgets" />
        <p v-else-if="workspace.budgetsClosedNotice" class="text-xs text-on-surface-variant">{{ workspace.budgetsClosedNotice }}</p>

        <AnalyseSectionsForm v-if="workspace.sections" :sections="workspace.sections" />

        <SimulationsPanel v-if="workspace.simulations" :simulations="workspace.simulations" />
    </div>
</template>
