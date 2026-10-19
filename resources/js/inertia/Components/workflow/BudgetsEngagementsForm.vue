<script setup>
/**
 * Totaux d'engagements et budgets par programme modifiables par l'analyste financier
 * (POST analyste.dossiers.instruction-budgets-engagements, mêmes noms de champs que le Blade).
 */
import { useForm } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';

const props = defineProps({
    budgets: { type: Object, required: true },
});

const form = useForm({
    engagements_sollicites_total: props.budgets.sollicite ?? '',
    engagements_en_cours_total: props.budgets.enCours ?? '',
    programme_budgets: props.budgets.programmes.map((p) => ({
        id: p.id,
        budget_appui_financier: p.financier ?? '',
        budget_appui_non_financier: p.nonFinancier ?? '',
    })),
});

const submit = () => form.post(props.budgets.url, { preserveScroll: true });

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Card title="Engagements et budgets programme (FCFA)">
        <form id="consultation-af-engagements-budgets" class="space-y-4 text-xs" @submit.prevent="submit">
            <p class="leading-relaxed text-on-surface-variant">
                Ajustez les totaux saisis en structuration et les budgets d’appui par ligne programme. Au moins un montant
                (financier ou non financier) par programme. Modifiable tant que le dossier d’instruction n’est pas clos.
            </p>
            <p v-if="form.errors.programme_budgets" class="rounded-lg border border-error/30 bg-error-container p-2 text-on-error-container">{{ form.errors.programme_budgets }}</p>
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div class="space-y-1">
                    <label for="af-sollicites" class="block font-semibold text-on-surface">Total engagements sollicités <span class="text-error">*</span></label>
                    <input id="af-sollicites" v-model="form.engagements_sollicites_total" type="number" min="0" step="1" required :class="inputClass">
                    <p v-if="form.errors.engagements_sollicites_total" class="text-error">{{ form.errors.engagements_sollicites_total }}</p>
                </div>
                <div class="space-y-1">
                    <label for="af-encours" class="block font-semibold text-on-surface">Total engagements en cours <span class="text-error">*</span></label>
                    <input id="af-encours" v-model="form.engagements_en_cours_total" type="number" min="0" step="1" required :class="inputClass">
                    <p v-if="form.errors.engagements_en_cours_total" class="text-error">{{ form.errors.engagements_en_cours_total }}</p>
                </div>
            </div>

            <p v-if="!budgets.programmes.length" class="text-on-surface-variant">Aucune ligne programme sur ce dossier : seuls les totaux d’engagements peuvent être mis à jour.</p>
            <table v-else class="w-full text-left">
                <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr><th class="py-2">Programme</th><th class="py-2 text-right">Appui financier</th><th class="py-2 text-right">Appui non financier</th></tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <tr v-for="(row, i) in form.programme_budgets" :key="row.id">
                        <td class="py-2 font-semibold text-on-surface">{{ budgets.programmes[i].name }}</td>
                        <td class="py-2 pl-2"><input v-model="row.budget_appui_financier" type="number" min="0" step="1" class="text-right" :class="inputClass"></td>
                        <td class="py-2 pl-2"><input v-model="row.budget_appui_non_financier" type="number" min="0" step="1" class="text-right" :class="inputClass"></td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end">
                <Button type="submit" variant="primary" icon="save" :disabled="form.processing">Enregistrer les montants</Button>
            </div>
        </form>
    </Card>
</template>
