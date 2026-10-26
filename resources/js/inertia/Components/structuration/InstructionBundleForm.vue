<script setup>
/**
 * Composition (ou correction après rejet) d'un dossier d'instruction multi-programmes par le chef de filière :
 * totaux d'engagements sollicités / en cours, puis une ligne par programme avec les budgets d'appui
 * financier et non financier. Envoi vers la route existante chef-filiere.clients.dossier-instruction.submit ;
 * toutes les règles (programmes déjà pris, budgets, états) restent contrôlées par le serveur.
 */
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';

const props = defineProps({
    composer: { type: Object, required: true },
});

const blankLine = () => ({ programme_id: '', budget_appui_financier: 0, budget_appui_non_financier: 0 });

const form = useForm({
    dossier_id: props.composer.editDossierId ?? '',
    engagements_sollicites_total: props.composer.values.engagements_sollicites_total ?? '',
    engagements_en_cours_total: props.composer.values.engagements_en_cours_total ?? '',
    chef_filiere_avis: props.composer.values.chef_filiere_avis ?? '',
    lignes: props.composer.values.lignes.map((l) => ({ ...blankLine(), ...l, programme_id: l.programme_id ?? '' })),
});

const fcfa = (v) => `${new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 }).format(Number(v) || 0).replace(/ | /g, ' ')} FCFA`;

const totals = computed(() => {
    const fin = form.lignes.reduce((s, l) => s + (Number(l.budget_appui_financier) || 0), 0);
    const nonFin = form.lignes.reduce((s, l) => s + (Number(l.budget_appui_non_financier) || 0), 0);
    return { fin, nonFin, general: fin + nonFin };
});

// Un programme ne peut figurer qu'une fois : les programmes déjà choisis sur d'autres lignes sont masqués.
const optionsFor = (index) => {
    const taken = form.lignes.filter((_, i) => i !== index).map((l) => Number(l.programme_id)).filter(Boolean);
    return props.composer.programmes.filter((p) => !taken.includes(p.value));
};
const canAdd = computed(() => form.lignes.length < props.composer.programmes.length);

const addLine = () => form.lignes.push(blankLine());
const removeLine = (index) => {
    if (form.lignes.length > 1) form.lignes.splice(index, 1);
};

const errorList = computed(() => Object.values(form.errors));

const submit = () => {
    form.transform((data) => ({ ...data, dossier_id: data.dossier_id || null })).post(props.composer.url, { preserveScroll: true });
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-5 shadow-xs">
        <header class="mb-4">
            <h2 class="text-sm font-bold text-on-surface">{{ composer.title }}</h2>
            <p class="mt-1 text-xs text-on-surface-variant">
                Sélectionnez un ou plusieurs programmes et indiquez pour chacun le budget d'appui financier et non financier (FCFA).
                Chaque dossier soumis est horodaté et transmis au chef d'agence pour validation.
            </p>
        </header>

        <form class="space-y-5" @submit.prevent="submit">
            <RejectionBanner v-if="composer.rejection" :rejection="composer.rejection" />
            <p v-if="composer.rejection" class="text-xs text-on-surface-variant">Vous pouvez corriger les programmes et budgets, puis resoumettre au chef d'agence.</p>

            <div v-if="errorList.length" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
                <p v-for="(message, i) in errorList" :key="i">{{ message }}</p>
            </div>

            <!-- Totaux d'engagements (délégation de pouvoir) -->
            <div class="grid grid-cols-1 gap-4 rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-4 md:grid-cols-2">
                <div class="space-y-1.5">
                    <label for="engagements_sollicites_total" class="block text-xs font-semibold text-on-surface">Total des engagements sollicités (FCFA)<span class="text-error"> *</span></label>
                    <input id="engagements_sollicites_total" v-model="form.engagements_sollicites_total" type="number" min="0" step="1" required :class="inputClass">
                    <p class="text-[11px] text-on-surface-variant">Sert à la délégation de pouvoir pour la clôture du dossier d'instruction.</p>
                </div>
                <div class="space-y-1.5">
                    <label for="engagements_en_cours_total" class="block text-xs font-semibold text-on-surface">Total des engagements en cours (FCFA)<span class="text-error"> *</span></label>
                    <input id="engagements_en_cours_total" v-model="form.engagements_en_cours_total" type="number" min="0" step="1" required :class="inputClass">
                </div>
            </div>

            <!-- Programmes et budgets -->
            <div class="space-y-3">
                <div v-for="(ligne, i) in form.lignes" :key="i" class="grid grid-cols-1 items-end gap-3 rounded-lg border border-surface-container-highest p-4 md:grid-cols-12">
                    <div class="space-y-1.5 md:col-span-5">
                        <label :for="`prg-${i}`" class="block text-xs font-semibold text-on-surface">Programme<span class="text-error"> *</span></label>
                        <select :id="`prg-${i}`" v-model="ligne.programme_id" required :class="inputClass">
                            <option value="" disabled>Choisir un programme…</option>
                            <option v-for="p in optionsFor(i)" :key="p.value" :value="p.value">{{ p.label }}</option>
                        </select>
                    </div>
                    <div class="space-y-1.5 md:col-span-3">
                        <label :for="`bf-${i}`" class="block text-xs font-semibold text-on-surface">Appui financier (FCFA)</label>
                        <input :id="`bf-${i}`" v-model="ligne.budget_appui_financier" type="number" min="0" step="1" :class="inputClass">
                    </div>
                    <div class="space-y-1.5 md:col-span-3">
                        <label :for="`bnf-${i}`" class="block text-xs font-semibold text-on-surface">Appui non financier (FCFA)</label>
                        <input :id="`bnf-${i}`" v-model="ligne.budget_appui_non_financier" type="number" min="0" step="1" :class="inputClass">
                    </div>
                    <div class="md:col-span-1 md:text-right">
                        <button
                            v-if="form.lignes.length > 1" type="button" title="Retirer ce programme"
                            class="inline-flex items-center rounded-lg p-2 text-error hover:bg-error-container/60"
                            @click="removeLine(i)"
                        >
                            <Icon name="delete" />
                        </button>
                    </div>
                </div>
                <p class="text-[11px] text-on-surface-variant">Renseignez au moins un budget (financier ou non financier) pour chaque programme.</p>
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div v-for="[label, value] in [['Total appui financier', totals.fin], ['Total appui non financier', totals.nonFin], ['Total général', totals.general]]" :key="label" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3">
                    <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ label }}</div>
                    <div class="text-sm font-bold text-on-surface">{{ fcfa(value) }}</div>
                </div>
            </div>

            <!-- Avis du chef de filière : obligatoire avant la soumission au chef d'agence -->
            <div class="space-y-1.5">
                <label class="block text-xs font-semibold text-on-surface">Avis du chef de filière<span class="text-error"> *</span></label>
                <RichTextEditor v-model="form.chef_filiere_avis" word-count />
                <p v-if="form.errors.chef_filiere_avis" class="text-xs text-error">{{ form.errors.chef_filiere_avis }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <Button icon="add" :disabled="!canAdd" @click="addLine">Ajouter un programme</Button>
                <Button type="submit" variant="success" icon="send" :disabled="form.processing">{{ composer.submitLabel }}</Button>
            </div>
        </form>
    </section>
</template>
