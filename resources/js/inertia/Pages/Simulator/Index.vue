<script setup>
/**
 * Simulateur de crédit : calcul d'échéancier via l'API JSON du module (simulator.api.*),
 * exports PDF / Excel et enregistrement d'un scénario rattaché ou non à un dossier.
 */
import { computed, reactive, ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import Modal from '@/Components/ui/Modal.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';

const props = defineProps({
    options: Object,
    defaults: Object,
    usuryRate: { type: Number, default: 0 },
    preselected: { type: Object, default: () => ({}) },
    urls: Object,
});

const initial = () => ({
    principal: props.preselected.principal ?? 10000000,
    annual_rate: 8,
    term_periods: 60,
    periodicity: props.options.periodicities[0]?.value ?? 'monthly',
    amortization_type: props.options.amortizationTypes[0]?.value ?? '',
    deferral_type: props.options.deferralTypes[0]?.value ?? '',
    deferral_periods: 0,
    currency: props.defaults.currency,
    first_period_date: props.defaults.firstPeriodDate,
    dossier_fee_fixed: 0,
    dossier_fee_pct: 0,
    insurance_pct: 0,
    insurance_basis: props.options.insuranceBases[0]?.value ?? '',
    vat_rate: 0,
});
const form = reactive(initial());
const result = ref(null);
const lastInput = ref(null);
const error = ref(null);
const loading = ref(false);

const persistOpen = ref(false);
const persist = reactive({
    name: '',
    target: props.preselected.dossier_instruction_programme_id ? 'programme' : (props.preselected.dossier_id ? 'dossier' : 'none'),
    dossier_id: props.preselected.dossier_id ?? '',
    dossier_instruction_programme_id: props.preselected.dossier_instruction_programme_id ?? '',
});

const usuryWarning = computed(() => props.usuryRate > 0 && Number(form.annual_rate) > props.usuryRate);
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
const fmt = (v) => (v === null || v === undefined ? '—' : Number(v).toLocaleString('fr-FR', { maximumFractionDigits: 2 }));

async function post(url, payload, expectFile = false) {
    const fd = new FormData();
    Object.entries(payload).forEach(([k, v]) => {
        if (v !== null && v !== undefined && v !== '') fd.append(k, v);
    });
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'X-Requested-With': 'XMLHttpRequest', Accept: expectFile ? '*/*' : 'application/json' },
        body: fd,
    });
    if (!res.ok) {
        let msg = 'Erreur lors de la requête.';
        try {
            const j = await res.json();
            msg = j.errors ? Object.values(j.errors).flat().join(' ') : (j.message || msg);
        } catch (e) { /* réponse non JSON */ }
        throw new Error(msg);
    }
    return expectFile ? res.blob() : res.json();
}

async function simulate() {
    error.value = null;
    loading.value = true;
    try {
        const payload = { ...form };
        result.value = await post(props.urls.simulate, payload);
        lastInput.value = payload;
    } catch (e) {
        error.value = e.message;
    } finally {
        loading.value = false;
    }
}

async function exportFile(url, filename) {
    if (!lastInput.value) return;
    try {
        const blob = await post(url, lastInput.value, true);
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        a.remove();
    } catch (e) {
        error.value = e.message;
    }
}

async function save() {
    if (!lastInput.value) return;
    const payload = { ...lastInput.value, name: persist.name };
    if (persist.target === 'dossier') payload.dossier_id = persist.dossier_id;
    if (persist.target === 'programme') payload.dossier_instruction_programme_id = persist.dossier_instruction_programme_id;
    try {
        const r = await post(props.urls.persist, payload);
        window.location.href = `${props.urls.scenarioBase}/${r.token}`;
    } catch (e) {
        error.value = e.message;
        persistOpen.value = false;
    }
}

const reset = () => {
    Object.assign(form, initial());
    result.value = null;
    lastInput.value = null;
    error.value = null;
};

const kpis = computed(() => (result.value ? [
    ['Première échéance', result.value.totals.first_payment],
    ['Échéance maximale', result.value.totals.max_payment],
    ['Total intérêts', result.value.totals.interest],
    ['Total frais', result.value.totals.fees],
    ['Total assurance', result.value.totals.insurance],
    ['Total TVA', result.value.totals.vat],
    ['Total à rembourser', result.value.totals.total_due],
    ['TEG calculé (%)', result.value.totals.computed_teg],
] : []));

const fields = [
    ['principal', 'Capital emprunté', 'number'], ['annual_rate', 'Taux annuel (%)', 'number'],
    ['term_periods', 'Durée (nombre de périodes)', 'number'], ['periodicity', 'Périodicité', 'periodicities'],
    ['amortization_type', 'Type d\'amortissement', 'amortizationTypes'], ['deferral_type', 'Type de différé', 'deferralTypes'],
    ['deferral_periods', 'Périodes de différé', 'number'], ['currency', 'Devise', 'currencies'],
    ['first_period_date', 'Première échéance', 'date'], ['dossier_fee_fixed', 'Frais de dossier (montant)', 'number'],
    ['dossier_fee_pct', 'Frais de dossier (% du capital)', 'number'], ['insurance_pct', 'Taux annuel d\'assurance (%)', 'number'],
    ['insurance_basis', 'Assiette de l\'assurance', 'insuranceBases'], ['vat_rate', 'TVA / TAF (%)', 'number'],
];
const optionsFor = (key) => (key === 'currencies' ? props.options.currencies.map((c) => ({ value: c, label: c })) : props.options[key]);
const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head title="Simulateur de crédit" />
    <PageHeader title="Simulateur de crédit" subtitle="Calcul d'échéancier et intégration au dossier d'instruction.">
        <template #actions>
            <Button :href="urls.scenarios" icon="list">Scénarios enregistrés</Button>
        </template>
    </PageHeader>

    <div v-if="error" class="flex items-start gap-3 rounded-xl border border-error/30 bg-error-container p-4 text-sm text-on-error-container">
        <Icon name="error" class="text-error" /><span>{{ error }}</span>
    </div>

    <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-12">
        <Card title="Paramètres" class="xl:col-span-5">
            <form class="grid grid-cols-1 gap-3 sm:grid-cols-2" @submit.prevent="simulate">
                <div v-for="[key, label, type] in fields" :key="key" class="space-y-1">
                    <label :for="`sim-${key}`" class="block text-xs font-semibold text-on-surface">{{ label }}</label>
                    <select v-if="!['number', 'date'].includes(type)" :id="`sim-${key}`" v-model="form[key]" :class="inputClass">
                        <option v-for="o in optionsFor(type)" :key="o.value" :value="o.value">{{ o.label }}</option>
                    </select>
                    <input v-else :id="`sim-${key}`" v-model="form[key]" :type="type" step="any" min="0" :class="inputClass">
                </div>
                <div v-if="usuryWarning" class="sm:col-span-2 rounded-lg border border-amber-200 bg-amber-50 p-3 text-xs text-amber-900">
                    Le taux saisi ({{ form.annual_rate }} %) dépasse le seuil d'usure indicatif ({{ usuryRate }} %).
                </div>
                <div class="flex gap-2 sm:col-span-2">
                    <Button type="submit" variant="primary" icon="calculate" :disabled="loading">Lancer la simulation</Button>
                    <Button @click="reset">Réinitialiser</Button>
                </div>
            </form>
        </Card>

        <div class="space-y-5 xl:col-span-7">
            <Card v-if="result" title="Résultat">
                <template #actions>
                    <div class="flex flex-wrap gap-2">
                        <Button icon="picture_as_pdf" @click="exportFile(urls.pdf, 'simulation-credit.pdf')">PDF</Button>
                        <Button icon="table_view" @click="exportFile(urls.xlsx, 'simulation-credit.xlsx')">Excel</Button>
                        <Button variant="primary" icon="save" @click="persistOpen = true">Enregistrer</Button>
                    </div>
                </template>
                <div class="mb-4 grid grid-cols-2 gap-3 md:grid-cols-4">
                    <div v-for="[label, value] in kpis" :key="label" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3">
                        <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ label }}</div>
                        <div class="text-sm font-bold text-on-surface">{{ fmt(value) }}</div>
                    </div>
                </div>
                <div class="max-h-[60vh] overflow-auto">
                    <table class="w-full text-xs tabular-nums">
                        <thead class="sticky top-0 bg-surface-container-low text-[11px] uppercase tracking-wider text-on-surface-variant">
                            <tr>
                                <th class="px-2 py-2 text-left">Période</th><th class="px-2 py-2 text-left">Date</th>
                                <th class="px-2 py-2 text-right">Capital début</th><th class="px-2 py-2 text-right">Capital remboursé</th>
                                <th class="px-2 py-2 text-right">Intérêts</th><th class="px-2 py-2 text-right">Assurance</th>
                                <th class="px-2 py-2 text-right">Frais</th><th class="px-2 py-2 text-right">TVA</th>
                                <th class="px-2 py-2 text-right">Échéance</th><th class="px-2 py-2 text-right">Capital fin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-container-highest">
                            <tr v-for="l in result.lines" :key="l.period_index" :class="l.is_deferred ? 'bg-amber-50/70' : ''">
                                <td class="px-2 py-1.5">{{ l.period_index }}</td><td class="px-2 py-1.5">{{ l.period_date ?? '—' }}</td>
                                <td class="px-2 py-1.5 text-right">{{ fmt(l.capital_due_start) }}</td><td class="px-2 py-1.5 text-right">{{ fmt(l.principal_paid) }}</td>
                                <td class="px-2 py-1.5 text-right">{{ fmt(l.interest_paid) }}</td><td class="px-2 py-1.5 text-right">{{ fmt(l.insurance_paid) }}</td>
                                <td class="px-2 py-1.5 text-right">{{ fmt(l.fees_paid) }}</td><td class="px-2 py-1.5 text-right">{{ fmt(l.vat_paid) }}</td>
                                <td class="px-2 py-1.5 text-right font-semibold">{{ fmt(l.total_payment) }}</td><td class="px-2 py-1.5 text-right">{{ fmt(l.capital_due_end) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
            <Card v-else>
                <div class="py-10 text-center text-xs text-on-surface-variant">
                    <Icon name="calculate" class="mb-2 text-4xl text-outline" />
                    <p>Saisissez les paramètres et lancez la simulation pour générer l'échéancier.</p>
                </div>
            </Card>
        </div>
    </div>

    <Modal :show="persistOpen" title="Enregistrer le scénario" @close="persistOpen = false">
        <div class="space-y-4 text-xs">
            <div class="space-y-1">
                <label for="persist-name" class="block font-semibold text-on-surface">Nom du scénario</label>
                <input id="persist-name" v-model="persist.name" type="text" maxlength="255" placeholder="Ex. constant 5 ans 8 %" :class="inputClass">
            </div>
            <fieldset class="space-y-2">
                <legend class="mb-1 font-semibold text-on-surface">Rattachement</legend>
                <label class="flex items-center gap-2"><input v-model="persist.target" type="radio" value="none" class="text-secondary focus:ring-secondary"> Scénario libre (non rattaché)</label>
                <label class="flex items-center gap-2"><input v-model="persist.target" type="radio" value="dossier" class="text-secondary focus:ring-secondary"> Rattacher au dossier</label>
                <input v-if="persist.target === 'dossier'" v-model="persist.dossier_id" type="number" min="1" placeholder="ID du dossier" :class="inputClass">
                <label class="flex items-center gap-2"><input v-model="persist.target" type="radio" value="programme" class="text-secondary focus:ring-secondary"> Rattacher à une ligne dossier-programme</label>
                <input v-if="persist.target === 'programme'" v-model="persist.dossier_instruction_programme_id" type="number" min="1" placeholder="ID de la ligne dossier-programme" :class="inputClass">
            </fieldset>
        </div>
        <template #footer>
            <Button @click="persistOpen = false">Annuler</Button>
            <Button variant="primary" @click="save">Enregistrer</Button>
        </template>
    </Modal>
</template>
