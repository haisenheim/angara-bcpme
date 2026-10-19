<script setup>
/**
 * Sept rubriques d'analyse de l'analyste financier : brouillon (instruction-avis-brouillon) ou soumission
 * au responsable exploitation (soumettre-exploitation). Verrouillées après soumission (sauf rejet), comme le Blade.
 */
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';

const props = defineProps({
    sections: { type: Object, required: true },
});

const form = useForm(Object.fromEntries(props.sections.items.map((s) => [s.name, s.html ?? ''])));

const hasSubstance = (html) => {
    const div = document.createElement('div');
    div.innerHTML = html ?? '';
    return (div.textContent ?? '').replace(/ /g, ' ').trim().length > 0;
};
const complete = computed(() => props.sections.items.every((s) => hasSubstance(form[s.name])));

const saveDraft = () => form.post(props.sections.draftUrl, { preserveScroll: true });
const submit = () => {
    if (!complete.value) return;
    if (!window.confirm('Soumettre au responsable exploitation ? Les rubriques ne seront plus modifiables (sauf rejet).')) return;
    form.post(props.sections.submitUrl, { preserveScroll: true });
};
</script>

<template>
    <Card title="Saisie analyste financier">
        <template v-if="sections.locked">
            <p class="mb-1 flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                <Icon name="lock" class="text-sm" />
                Transmis au responsable exploitation<template v-if="sections.submitted"> le {{ sections.submitted.at }}<template v-if="sections.submitted.by"> — {{ sections.submitted.by }}</template></template>
            </p>
            <p class="mb-4 text-xs text-on-surface-variant">Les rubriques ne sont plus modifiables après soumission.</p>
            <div class="space-y-3">
                <div v-for="(s, i) in sections.items" :key="s.name" class="rounded-lg border border-surface-container-highest p-3">
                    <div class="mb-1 text-xs font-semibold text-on-surface">{{ i + 1 }}. {{ s.label }}</div>
                    <SafeHtml v-if="s.filled" :html="s.html" class="text-xs leading-relaxed text-on-surface" />
                    <p v-else class="text-xs text-on-surface-variant">—</p>
                </div>
            </div>
        </template>

        <form v-else class="space-y-4" @submit.prevent>
            <p class="text-xs leading-relaxed text-on-surface-variant">
                Sept rubriques à renseigner, en complément de la grille de notation et de l’import DSF.
                « Enregistrer le brouillon » permet de reprendre plus tard ; la soumission au responsable exploitation exige les sept rubriques complètes.
            </p>
            <div v-for="(s, i) in sections.items" :id="`af-saisie-${i + 1}`" :key="s.name" class="space-y-1.5">
                <label class="flex items-center gap-2 text-xs font-semibold text-on-surface">
                    {{ i + 1 }}. {{ s.label }}
                    <Icon v-if="hasSubstance(form[s.name])" name="check_circle" class="text-sm text-emerald-600" />
                </label>
                <RichTextEditor v-model="form[s.name]" min-height="8rem" />
                <p v-if="form.errors[s.name]" class="text-xs text-error">{{ form.errors[s.name] }}</p>
            </div>
            <p v-if="form.errors.submission" class="rounded-lg border border-error/30 bg-error-container p-2 text-xs text-on-error-container">{{ form.errors.submission }}</p>
            <p v-if="sections.draftSavedAt" class="text-[11px] text-on-surface-variant">Dernière sauvegarde du brouillon : le {{ sections.draftSavedAt }}.</p>
            <div class="flex flex-wrap items-center gap-2">
                <Button :disabled="form.processing" icon="save" @click="saveDraft">Enregistrer le brouillon</Button>
                <Button variant="success" :disabled="form.processing || !complete" icon="send" @click="submit">Soumettre au responsable exploitation pour validation</Button>
            </div>
            <p class="text-[11px] text-on-surface-variant">Les sept rubriques doivent contenir du texte significatif avant soumission au responsable exploitation.</p>
        </form>
    </Card>
</template>
