<script setup>
/**
 * Fiche programme commune à tous les profils (contrat : ProgrammePagePresenter::show).
 * Onglets, consommation budgétaire (direction) et actions d'édition (admin) sont décidés par le serveur.
 */
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Card from '@/Components/ui/Card.vue';
import Button from '@/Components/ui/Button.vue';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';
import SimpleTable from '@/Components/programmes/SimpleTable.vue';

const props = defineProps({
    programme: Object,
    details: Array,
    kpis: Array,
    tabs: Array,
    budget: { type: Object, default: null },
    actions: { type: Array, default: () => [] },
    editUrl: { type: String, default: null },
    indexUrl: String,
});

const activeTab = ref(props.tabs[0]?.key);
const opened = ref(null);
</script>

<template>
    <Head :title="programme.name" />

    <!-- En-tête -->
    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div>
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <StatusBadge variant="info">Programme</StatusBadge>
                <StatusBadge v-if="programme.active !== null" :variant="programme.active ? 'success' : 'neutral'">{{ programme.active ? 'Actif' : 'Inactif' }}</StatusBadge>
            </div>
            <h1 class="text-xl font-bold tracking-tight text-on-surface">{{ programme.name }}</h1>
            <p class="mt-1 text-xs text-on-surface-variant">
                <span class="font-semibold text-on-surface">Convention :</span> {{ programme.convention || '—' }}
                <span class="mx-2">·</span>
                <span class="font-semibold text-on-surface">Signataire :</span> {{ programme.signataire || '—' }}
            </p>
        </div>
        <div class="flex shrink-0 flex-wrap items-center gap-2.5">
            <Link :href="indexUrl" class="inline-flex items-center gap-2 rounded-lg border border-surface-container-highest bg-white px-4 py-2.5 text-xs font-semibold text-on-surface shadow-2xs hover:bg-surface-container-low">
                <Icon name="arrow_back" class="text-base" /> Liste des programmes
            </Link>
            <Button v-for="action in actions" :key="action.key" :variant="action.variant" :icon="action.icon" @click="opened = action">{{ action.label }}</Button>
            <Link v-if="editUrl" :href="editUrl" class="inline-flex items-center gap-2 rounded-lg bg-primary-container px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-black">
                <Icon name="edit" class="text-base" /> Modifier le programme
            </Link>
        </div>
    </div>

    <!-- Indicateurs -->
    <div class="grid grid-cols-2 gap-4" :class="kpis.length > 3 ? 'md:grid-cols-4' : 'md:grid-cols-3'">
        <div v-for="kpi in kpis" :key="kpi.label" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <div class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ kpi.label }}</div>
            <div class="mt-2 text-xl font-bold tracking-tight" :class="kpi.tone === 'brand' ? 'text-secondary' : 'text-on-surface'">{{ kpi.value }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
        <!-- Données contractuelles -->
        <Card title="Données contractuelles">
            <dl class="divide-y divide-surface-container-highest text-xs">
                <div v-for="d in details" :key="d.label" class="flex items-start justify-between gap-4 py-2">
                    <dt class="text-on-surface-variant">{{ d.label }}</dt>
                    <dd class="text-right" :class="d.strong ? 'font-bold text-on-surface' : 'font-semibold text-on-surface'">{{ d.value || '—' }}</dd>
                </div>
            </dl>
        </Card>

        <!-- Onglets -->
        <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs lg:col-span-2">
            <nav class="flex gap-1 overflow-x-auto border-b border-surface-container-highest px-3 pt-2" role="tablist">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    type="button"
                    role="tab"
                    :aria-selected="activeTab === tab.key"
                    class="-mb-px whitespace-nowrap border-b-2 px-3 py-2.5 text-xs font-semibold transition-colors"
                    :class="activeTab === tab.key ? 'border-secondary text-on-surface' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
                    @click="activeTab = tab.key"
                >
                    {{ tab.label }}
                    <span class="ml-1 rounded bg-surface-container px-1.5 py-0.5 text-[10px] font-bold text-on-surface-variant">{{ tab.rows.length }}</span>
                </button>
            </nav>
            <div class="p-5">
                <template v-for="tab in tabs" :key="tab.key">
                    <div v-if="activeTab === tab.key" class="space-y-3">
                        <p v-if="tab.hint" class="text-xs text-on-surface-variant">{{ tab.hint }}</p>
                        <SimpleTable :table="tab" />
                    </div>
                </template>
            </div>
        </section>
    </div>

    <!-- Consommation des budgets d'appui (direction générale) -->
    <template v-if="budget">
        <Card title="Consommation des budgets d'appui">
            <p class="mb-4 text-xs text-on-surface-variant">
                Montants affectés dans les dossiers d'instruction <strong class="text-on-surface">déjà validés par le chef d'agence</strong>
                (budgets financier et non financier saisis par le chef de filière pour ce programme).
            </p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div v-for="c in budget.cards" :key="c.label" class="rounded-lg border border-surface-container-highest p-4">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-xs font-bold text-on-surface">{{ c.label }}</span>
                        <StatusBadge v-if="c.overflow" variant="danger">Dépasse l'enveloppe</StatusBadge>
                    </div>
                    <div class="space-y-1 text-xs text-on-surface-variant">
                        <div>Enveloppe convention : <span class="font-semibold text-on-surface">{{ c.envelope }}</span></div>
                        <div>Engagé sur dossiers validés agence : <span class="font-semibold text-on-surface">{{ c.engaged }}</span></div>
                    </div>
                    <div class="mt-3 flex items-center justify-between text-xs">
                        <span class="text-on-surface-variant">Taux de consommation</span>
                        <span class="font-bold text-on-surface">{{ c.rate }}</span>
                    </div>
                    <div class="mt-1 h-2 overflow-hidden rounded bg-surface-container-low">
                        <div class="h-full rounded" :class="c.overflow ? 'bg-error' : 'bg-secondary'" :style="{ width: `${c.bar}%` }"></div>
                    </div>
                    <div class="mt-2 text-[11px] text-on-surface-variant">Reste indicatif : {{ c.remaining }}</div>
                </div>
            </div>
            <p class="mt-4 rounded-lg bg-surface-container-low p-3 text-xs text-on-surface-variant">{{ budget.coordination }}</p>
        </Card>

        <Card title="Synthèse des taux de consommation">
            <SimpleTable :table="budget.synthese" />
            <p class="mt-3 text-[11px] text-on-surface-variant">
                Le taux total rapporte les engagements dossier (financier + non financier) à la somme des enveloppes conventionnelles,
                coordination incluse. Un taux supérieur à 100 % signale un dépassement sur au moins une enveloppe.
            </p>
        </Card>

        <Card v-if="budget.appuis" title="Appuis prévus au catalogue — ventilation indicative">
            <p class="mb-3 text-xs text-on-surface-variant">
                En l'absence de sous-enveloppes par appui, chaque ligne d'une même nature reçoit une part égale de l'enveloppe et des montants
                engagés de sa nature ; le taux de consommation est celui de la nature.
            </p>
            <SimpleTable :table="budget.appuis" />
        </Card>

        <Card title="Détail par dossier (validation chef d'agence)">
            <SimpleTable :table="budget.lignes" />
            <p class="mt-3 text-[11px] text-on-surface-variant">
                % env. fin. / non fin. : part de l'enveloppe programme de la nature correspondante représentée par la ligne dossier
                (la somme des lignes peut dépasser 100 % en cas de dépassement d'enveloppe).
            </p>
        </Card>
    </template>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
</template>
