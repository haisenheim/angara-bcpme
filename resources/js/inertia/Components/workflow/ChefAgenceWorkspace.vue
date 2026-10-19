<script setup>
/**
 * Espace de travail du chef d'agence : résumé, notation PME, grille de notation, avis du chef d'agence.
 * Validation / rejet de la structuration et transmission à l'exploitation sont dans le panneau d'actions.
 */
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';
import GrilleNotation from '@/Components/workflow/GrilleNotation.vue';
import SmeCard from '@/Components/workflow/SmeCard.vue';

const props = defineProps({
    workspace: { type: Object, required: true },
});

const form = useForm({ instruction_agence_ca_avis: props.workspace.avis?.html ?? '' });
const save = () => form.post(props.workspace.avis.saveUrl, { preserveScroll: true });
</script>

<template>
    <div class="space-y-5">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            <div class="space-y-5">
                <Card title="Résumé du dossier">
                    <dl class="space-y-2 text-xs">
                        <div v-for="row in workspace.summary" :key="row.label">
                            <dt class="text-on-surface-variant">{{ row.label }}</dt>
                            <dd class="font-semibold" :class="row.danger ? 'text-error' : 'text-on-surface'">{{ row.value }}</dd>
                        </div>
                    </dl>
                </Card>
                <SmeCard :sme="workspace.sme" />
            </div>
            <div class="xl:col-span-2">
                <GrilleNotation :grille="workspace.grille" />
            </div>
        </div>

        <Card v-if="workspace.avis" title="Avis du chef d'agence">
            <div class="space-y-3 text-xs">
                <p class="text-on-surface-variant">
                    <template v-if="workspace.avis.editable">Enregistrez un avis, puis transmettez le dossier au responsable exploitation (panneau d’actions).</template>
                    <template v-else>Lecture seule — le dossier a été transmis au responsable exploitation ; l’avis ne peut plus être modifié.</template>
                </p>
                <p v-if="workspace.avis.saved" class="text-on-surface-variant">
                    <strong class="text-on-surface">Dernier enregistrement :</strong> {{ workspace.avis.saved.at }}<template v-if="workspace.avis.saved.by"> — {{ workspace.avis.saved.by }}</template>
                </p>
                <p v-if="workspace.avis.transmitted" class="flex items-center gap-1.5 font-semibold text-emerald-700">
                    <Icon name="lock" class="text-sm" />
                    Transmis au responsable exploitation le {{ workspace.avis.transmitted.at }}<template v-if="workspace.avis.transmitted.by"> — {{ workspace.avis.transmitted.by }}</template>
                </p>
                <template v-if="workspace.avis.editable">
                    <RichTextEditor v-model="form.instruction_agence_ca_avis" min-height="12rem" />
                    <p v-if="form.errors.instruction_agence_ca_avis" class="text-error">{{ form.errors.instruction_agence_ca_avis }}</p>
                    <div class="flex justify-end">
                        <Button variant="primary" icon="save" :disabled="form.processing" @click="save">Enregistrer l’avis</Button>
                    </div>
                </template>
                <SafeHtml v-else :html="workspace.avis.html" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3 leading-relaxed text-on-surface" />
            </div>
        </Card>
    </div>
</template>
