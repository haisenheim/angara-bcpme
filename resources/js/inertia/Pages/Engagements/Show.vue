<script setup>
/**
 * Grille des engagements d'une entreprise (sections → rubriques → produits → lignes par partenaire).
 * Lecture pour tous les profils autorisés ; saisie (création / modification / suppression de lignes,
 * nouveau partenaire) uniquement si `canEdit` (EngagementAccessService). Les envois passent par les
 * routes existantes (engagements.lignes.*, engagements.partenaires.store).
 */
import { computed, reactive, ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Modal from '@/Components/ui/Modal.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

const props = defineProps({
    entreprise: { type: Object, required: true },
    stats: { type: Array, default: () => [] },
    tree: { type: Array, default: () => [] },
    filters: { type: Object, default: () => ({ partenaire_id: '', search: '' }) },
    partenaireGroups: { type: Array, default: () => [] },
    produits: { type: Array, default: () => [] },
    statuts: { type: Array, default: () => [] },
    kindLabels: { type: Array, default: () => [] },
    canEdit: { type: Boolean, default: false },
    reports: { type: Array, default: () => [] },
    endpoints: { type: Object, required: true },
});

/* ---------- Filtres ---------- */
const values = reactive({ partenaire_id: props.filters.partenaire_id ?? '', search: props.filters.search ?? '' });
let timer = null;
const query = () => Object.fromEntries(Object.entries(values).filter(([, v]) => v !== '' && v !== null));
const apply = () => router.get(props.endpoints.show, query(), { preserveState: true, preserveScroll: true, replace: true, only: ['tree', 'stats', 'filters'] });
const applyDebounced = () => {
    clearTimeout(timer);
    timer = setTimeout(apply, 250);
};
const reset = () => {
    values.partenaire_id = '';
    values.search = '';
    apply();
};

/* ---------- Exports ---------- */
const exportOpen = ref(null);
const exportUrl = (format, report) => {
    const p = new URLSearchParams({ format, report, ...query() });
    return `${props.endpoints.export}?${p.toString()}`;
};

/* ---------- Grille ---------- */
const nf = new Intl.NumberFormat('fr-FR', { maximumFractionDigits: 0 });
const fmt = (v) => nf.format(Number(v || 0));
const fmtDate = (s) => {
    const m = String(s ?? '').match(/^(\d{4})-(\d{2})-(\d{2})/);
    return m ? `${m[3]}/${m[2]}/${m[1]}` : (s || '—');
};
const statutLabel = (code) => props.statuts.find((s) => s.value === code)?.label ?? code ?? '—';

const rows = computed(() => {
    const out = [];
    const walk = (node, depth) => {
        out.push({ kind: 'node', node, depth });
        (node.lignes ?? []).forEach((ligne) => out.push({ kind: 'ligne', ligne, node, depth }));
        (node.children ?? []).forEach((c) => walk(c, depth + 1));
    };
    props.tree.forEach((n) => walk(n, 0));
    return out;
});

const nodeClass = {
    section: 'bg-primary-container text-white font-bold',
    rubrique: 'bg-surface-container font-semibold text-on-surface',
    nature: 'bg-surface-container-low font-semibold text-on-surface',
    produit: 'text-on-surface',
};

/* ---------- Saisie des lignes (ActionDialog) ---------- */
const opened = ref(null);
const partenaireOptions = computed(() => props.partenaireGroups.flatMap((g) => g.options.map((o) => ({ value: o.value, label: `${o.label} (${g.label})` }))));

const ligneFields = (ligne = {}, categorieId = '') => [
    { name: 'engagement_categorie_id', label: 'Catégorie / produit', type: 'select', required: true, value: String(ligne.engagement_categorie_id ?? categorieId ?? ''), options: props.produits },
    { name: 'partenaire_id', label: 'Partenaire financier (banque, EMF ou autre)', type: 'select', value: ligne.partenaire_id ? String(ligne.partenaire_id) : '', options: [{ value: '', label: '— Aucun partenaire spécifique —' }, ...partenaireOptions.value] },
    { name: 'encours_initial', label: 'Encours — initial', type: 'number', value: ligne.encours_initial ?? 0 },
    { name: 'encours_actuel', label: 'Encours — actuel', type: 'number', value: ligne.encours_actuel ?? 0 },
    { name: 'encours_remboursement_n1', label: 'Encours — remboursement N-1', type: 'number', value: ligne.encours_remboursement_n1 ?? 0 },
    { name: 'encours_retards', label: 'Encours — retards', type: 'number', value: ligne.encours_retards ?? 0 },
    { name: 'encours_impayes', label: 'Encours — impayés', type: 'number', value: ligne.encours_impayes ?? 0 },
    { name: 'encours_statut', label: 'Encours — statut', type: 'select', value: ligne.encours_statut ?? '', options: [{ value: '', label: '—' }, ...props.statuts] },
    { name: 'encours_date_validite', label: 'Encours — date de validité', type: 'date', value: ligne.encours_date_validite ?? '' },
    { name: 'sollicite_montant', label: 'Sollicité — montant', type: 'number', value: ligne.sollicite_montant ?? 0 },
    { name: 'sollicite_date_validite', label: 'Sollicité — date de validité', type: 'date', value: ligne.sollicite_date_validite ?? '' },
    { name: 'commentaire', label: 'Note / commentaire', type: 'textarea', value: ligne.commentaire ?? '' },
];

const openCreate = (node) => {
    opened.value = {
        key: `create-${node.id}`,
        title: `Nouvelle ligne d’engagement — ${node.libelle}`,
        wide: true,
        fields: ligneFields({}, String(node.id)),
        submits: [{ label: 'Enregistrer', url: props.endpoints.ligneStore, method: 'post', variant: 'primary' }],
    };
};
const openEdit = (ligne) => {
    opened.value = {
        key: `edit-${ligne.id}`,
        title: 'Modifier la ligne',
        wide: true,
        fields: ligneFields(ligne),
        submits: [{ label: 'Enregistrer', url: props.endpoints.ligneUpdate.replace('__ID__', ligne.id), method: 'put', variant: 'primary' }],
    };
};
const destroy = (ligne) => {
    if (!window.confirm('Supprimer cette ligne ?')) return;
    router.delete(props.endpoints.ligneDestroy.replace('__ID__', ligne.id), { preserveScroll: true });
};

/* ---------- Détail d'une ligne ---------- */
const detail = ref(null);

/* ---------- Nouveau partenaire (endpoint JSON existant) ---------- */
const partenaire = reactive({ open: false, processing: false, errors: {}, data: { kind: '', name: '', siege: '', address: '' } });
const openPartenaire = () => {
    Object.assign(partenaire, { open: true, errors: {}, data: { kind: props.kindLabels[0]?.value ?? '', name: '', siege: '', address: '' } });
};
const savePartenaire = async () => {
    partenaire.processing = true;
    partenaire.errors = {};
    try {
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
        const res = await fetch(props.endpoints.partenaireStore, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(partenaire.data),
        });
        if (res.status === 422) {
            const json = await res.json();
            partenaire.errors = Object.fromEntries(Object.entries(json.errors ?? {}).map(([k, v]) => [k, v[0]]));
            return;
        }
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        partenaire.open = false;
        router.reload({ only: ['partenaireGroups'] });
    } catch (e) {
        partenaire.errors = { _: `Erreur lors de la création du partenaire : ${e.message}` };
    } finally {
        partenaire.processing = false;
    }
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
const filterClass = 'rounded-lg border-surface-container-highest bg-surface-container-low text-xs focus:border-secondary focus:ring-secondary';
const th = 'px-2 py-2 font-semibold';
</script>

<template>
    <Head :title="`Grille des engagements — ${entreprise.name}`" />
    <PageHeader title="Grille des engagements" :subtitle="entreprise.niu ? `${entreprise.name} — NIU ${entreprise.niu}` : entreprise.name" />

    <!-- Synthèse -->
    <div class="grid grid-cols-2 gap-4 md:grid-cols-3 xl:grid-cols-6">
        <div v-for="s in stats" :key="s.key" class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-4 shadow-xs">
            <span class="text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ s.label }}</span>
            <div class="mt-1 text-lg font-bold tracking-tight" :class="s.tone === 'danger' ? 'text-error' : s.tone === 'primary' ? 'text-secondary' : 'text-on-surface'">{{ s.value }}</div>
            <span class="text-[11px] text-on-surface-variant">{{ s.unit }}</span>
        </div>
    </div>

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest shadow-xs">
        <div class="flex flex-wrap items-end justify-between gap-3 border-b border-surface-container-highest p-4">
            <div>
                <h2 class="text-sm font-bold text-on-surface">Grille d'engagements</h2>
                <p class="text-xs text-on-surface-variant">Sections, rubriques et produits — saisie par partenaire financier (banque, EMF ou autre).</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <select v-model="values.partenaire_id" :class="filterClass" class="min-w-48" aria-label="Partenaire" @change="apply">
                    <option value="">Tous partenaires</option>
                    <optgroup v-for="g in partenaireGroups" :key="g.label" :label="g.label">
                        <option v-for="o in g.options" :key="o.value" :value="o.value">{{ o.label }}</option>
                    </optgroup>
                </select>
                <div class="relative">
                    <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
                    <input v-model="values.search" type="search" placeholder="Rechercher…" :class="filterClass" class="pl-8" aria-label="Recherche" @input="applyDebounced">
                </div>
                <button type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-xs font-semibold text-on-surface-variant hover:bg-surface-container-low" @click="reset">
                    <Icon name="restart_alt" class="text-base" /> Réinitialiser
                </button>
                <Button v-if="canEdit" icon="add_business" @click="openPartenaire">Nouveau partenaire</Button>
                <div v-for="fmtKey in ['pdf', 'xlsx']" :key="fmtKey" class="relative">
                    <button type="button" class="inline-flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-white" :class="fmtKey === 'pdf' ? 'bg-primary-container hover:bg-black' : 'bg-brand hover:bg-brand-hover'" @click="exportOpen = exportOpen === fmtKey ? null : fmtKey">
                        <Icon :name="fmtKey === 'pdf' ? 'picture_as_pdf' : 'table_view'" class="text-base" /> Exporter {{ fmtKey === 'pdf' ? 'PDF' : 'Excel' }} <Icon name="expand_more" class="text-base" />
                    </button>
                    <div v-if="exportOpen === fmtKey" class="absolute right-0 z-30 mt-1 w-72 rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg" @mouseleave="exportOpen = null">
                        <template v-for="r in reports.filter((x) => x[fmtKey])" :key="r.id">
                            <a v-if="r.enabled" :href="exportUrl(fmtKey, r.id)" class="block px-3 py-2 text-xs hover:bg-surface-container-low">
                                <span class="font-semibold text-on-surface">{{ r.label }}</span>
                                <span v-if="r.description" class="mt-0.5 block text-[11px] text-on-surface-variant">{{ r.description }}</span>
                            </a>
                            <div v-else class="cursor-not-allowed px-3 py-2 text-xs text-on-surface-variant opacity-70">
                                <span class="font-semibold">{{ r.label }}</span>
                                <span v-if="r.description" class="mt-0.5 block text-[11px]">{{ r.description }}</span>
                                <span class="mt-0.5 block text-[11px] italic">À venir</span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1200px] border-collapse text-xs">
                <thead class="bg-surface-container-low/60 text-[11px] uppercase tracking-wider text-on-surface-variant">
                    <tr class="border-b border-surface-container-highest">
                        <th rowspan="2" class="px-3 py-2 text-left font-semibold">Engagement</th>
                        <th rowspan="2" class="px-2 py-2 text-left font-semibold">Partenaire</th>
                        <th colspan="7" class="border-x border-surface-container-highest px-2 py-2 text-center font-semibold">Encours</th>
                        <th colspan="2" class="border-r border-surface-container-highest px-2 py-2 text-center font-semibold">Sollicité</th>
                        <th colspan="2" class="border-r border-surface-container-highest px-2 py-2 text-center font-semibold">Total</th>
                        <th rowspan="2" class="px-2 py-2 text-center font-semibold">Actions</th>
                    </tr>
                    <tr class="border-b border-surface-container-highest">
                        <th :class="th" class="border-l border-surface-container-highest text-right">Initial</th>
                        <th :class="th" class="text-right">Actuel</th>
                        <th :class="th" class="text-right">Remb. N-1</th>
                        <th :class="th" class="text-right">Retards</th>
                        <th :class="th" class="text-right">Impayés</th>
                        <th :class="th" class="text-left">Statut</th>
                        <th :class="th" class="border-r border-surface-container-highest text-left">Date validité</th>
                        <th :class="th" class="text-right">Montant</th>
                        <th :class="th" class="border-r border-surface-container-highest text-left">Date validité</th>
                        <th :class="th" class="text-right">Montant</th>
                        <th :class="th" class="border-r border-surface-container-highest text-right">Variation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-highest">
                    <template v-for="(r, i) in rows" :key="i">
                        <tr v-if="r.kind === 'node'" :class="nodeClass[r.node.type] ?? ''">
                            <td class="px-3 py-1.5"><span :style="{ paddingLeft: `${r.depth * 18}px` }">{{ r.node.libelle }}</span></td>
                            <td class="px-2 py-1.5">—</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.encours_initial) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.encours_actuel) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.encours_remboursement_n1) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.encours_retards) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.encours_impayes) }}</td>
                            <td class="px-2 py-1.5">—</td>
                            <td class="px-2 py-1.5">—</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.node.sollicite_montant) }}</td>
                            <td class="px-2 py-1.5">—</td>
                            <td class="px-2 py-1.5 text-right font-semibold">{{ fmt((r.node.encours_actuel || 0) + (r.node.sollicite_montant || 0)) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt((r.node.sollicite_montant || 0) - (r.node.encours_actuel || 0)) }}</td>
                            <td class="px-2 py-1.5 text-center">
                                <button v-if="r.node.is_leaf && canEdit" type="button" class="rounded p-1 text-secondary hover:bg-brand-subtle" title="Ajouter une ligne" @click="openCreate(r.node)">
                                    <Icon name="add" class="text-base" />
                                </button>
                            </td>
                        </tr>
                        <tr v-else class="bg-surface-container-lowest hover:bg-surface-container-low/50">
                            <td class="px-3 py-1.5 text-on-surface-variant"><span :style="{ paddingLeft: `${r.depth * 18 + 24}px` }">↳ {{ r.ligne.partenaire_nom || '—' }}</span></td>
                            <td class="px-2 py-1.5"><span class="rounded border border-surface-container-highest bg-surface-container-low px-1.5 py-0.5 text-[11px]">{{ r.ligne.partenaire_kind_label || '—' }}</span></td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.ligne.encours_initial) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.ligne.encours_actuel) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.ligne.encours_remboursement_n1) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.ligne.encours_retards) }}</td>
                            <td class="px-2 py-1.5 text-right" :class="r.ligne.encours_impayes > 0 ? 'font-semibold text-error' : ''">{{ fmt(r.ligne.encours_impayes) }}</td>
                            <td class="px-2 py-1.5">{{ r.ligne.encours_statut ? statutLabel(r.ligne.encours_statut) : '—' }}</td>
                            <td class="px-2 py-1.5">{{ fmtDate(r.ligne.encours_date_validite) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt(r.ligne.sollicite_montant) }}</td>
                            <td class="px-2 py-1.5">{{ fmtDate(r.ligne.sollicite_date_validite) }}</td>
                            <td class="px-2 py-1.5 text-right font-semibold">{{ fmt((r.ligne.encours_actuel || 0) + (r.ligne.sollicite_montant || 0)) }}</td>
                            <td class="px-2 py-1.5 text-right">{{ fmt((r.ligne.sollicite_montant || 0) - (r.ligne.encours_actuel || 0)) }}</td>
                            <td class="whitespace-nowrap px-2 py-1.5 text-center">
                                <button type="button" class="rounded p-1 text-on-surface-variant hover:bg-surface-container-high" title="Consulter le détail" @click="detail = r.ligne">
                                    <Icon name="visibility" class="text-base" />
                                </button>
                                <template v-if="canEdit">
                                    <button type="button" class="rounded p-1 text-on-surface-variant hover:bg-surface-container-high" title="Modifier" @click="openEdit(r.ligne)">
                                        <Icon name="edit" class="text-base" />
                                    </button>
                                    <button type="button" class="rounded p-1 text-error hover:bg-error-container" title="Supprimer" @click="destroy(r.ligne)">
                                        <Icon name="delete" class="text-base" />
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <tr v-if="!rows.length">
                        <td colspan="14" class="px-4 py-10 text-center text-on-surface-variant">Aucun engagement à afficher.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />

    <!-- Détail d'une ligne (lecture) -->
    <Modal :show="!!detail" title="Détail de la ligne d’engagement" wide @close="detail = null">
        <dl v-if="detail" class="grid grid-cols-1 gap-x-6 gap-y-3 text-xs sm:grid-cols-2">
            <div><dt class="text-on-surface-variant">Catégorie / produit</dt><dd class="font-semibold text-on-surface">{{ detail.engagement_categorie_libelle || '—' }}</dd></div>
            <div><dt class="text-on-surface-variant">Partenaire</dt><dd class="font-semibold text-on-surface">{{ detail.partenaire_nom || '—' }} <span class="font-normal text-on-surface-variant">{{ detail.partenaire_kind_label }}</span></dd></div>
            <div><dt class="text-on-surface-variant">Encours initial</dt><dd class="font-semibold">{{ fmt(detail.encours_initial) }}</dd></div>
            <div><dt class="text-on-surface-variant">Encours actuel</dt><dd class="font-semibold">{{ fmt(detail.encours_actuel) }}</dd></div>
            <div><dt class="text-on-surface-variant">Remboursement N-1</dt><dd class="font-semibold">{{ fmt(detail.encours_remboursement_n1) }}</dd></div>
            <div><dt class="text-on-surface-variant">Retards</dt><dd class="font-semibold">{{ fmt(detail.encours_retards) }}</dd></div>
            <div><dt class="text-on-surface-variant">Impayés</dt><dd class="font-semibold" :class="detail.encours_impayes > 0 ? 'text-error' : ''">{{ fmt(detail.encours_impayes) }}</dd></div>
            <div><dt class="text-on-surface-variant">Statut</dt><dd class="font-semibold">{{ detail.encours_statut_label || statutLabel(detail.encours_statut) }}</dd></div>
            <div><dt class="text-on-surface-variant">Date de validité (encours)</dt><dd class="font-semibold">{{ fmtDate(detail.encours_date_validite) }}</dd></div>
            <div><dt class="text-on-surface-variant">Sollicité</dt><dd class="font-semibold">{{ fmt(detail.sollicite_montant) }}</dd></div>
            <div><dt class="text-on-surface-variant">Date de validité (sollicité)</dt><dd class="font-semibold">{{ fmtDate(detail.sollicite_date_validite) }}</dd></div>
            <div><dt class="text-on-surface-variant">Total / variation</dt><dd class="font-semibold">{{ fmt(detail.total_montant) }} / {{ fmt(detail.variation) }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-on-surface-variant">Commentaire</dt><dd class="whitespace-pre-line" :class="detail.commentaire ? 'text-on-surface' : 'italic text-on-surface-variant'">{{ detail.commentaire || '—' }}</dd></div>
            <div v-if="detail.updated_by" class="text-[11px] text-on-surface-variant sm:col-span-2">Dernière mise à jour : {{ detail.updated_by }}<template v-if="detail.updated_at"> — {{ detail.updated_at }}</template></div>
        </dl>
    </Modal>

    <!-- Nouveau partenaire -->
    <Modal :show="partenaire.open" title="Nouveau partenaire financier" @close="partenaire.open = false">
        <form class="space-y-4" @submit.prevent="savePartenaire">
            <p v-if="partenaire.errors._" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">{{ partenaire.errors._ }}</p>
            <div class="space-y-1.5">
                <label for="p-kind" class="block text-xs font-semibold">Type <span class="text-error">*</span></label>
                <select id="p-kind" v-model="partenaire.data.kind" required :class="inputClass">
                    <option v-for="k in kindLabels" :key="k.value" :value="k.value">{{ k.label }}</option>
                </select>
                <p v-if="partenaire.errors.kind" class="text-xs text-error">{{ partenaire.errors.kind }}</p>
            </div>
            <div class="space-y-1.5">
                <label for="p-name" class="block text-xs font-semibold">Raison sociale <span class="text-error">*</span></label>
                <input id="p-name" v-model="partenaire.data.name" type="text" required maxlength="255" :class="inputClass">
                <p v-if="partenaire.errors.name" class="text-xs text-error">{{ partenaire.errors.name }}</p>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-1.5">
                    <label for="p-siege" class="block text-xs font-semibold">Siège</label>
                    <input id="p-siege" v-model="partenaire.data.siege" type="text" maxlength="255" :class="inputClass">
                </div>
                <div class="space-y-1.5">
                    <label for="p-address" class="block text-xs font-semibold">Adresse / contact</label>
                    <input id="p-address" v-model="partenaire.data.address" type="text" maxlength="1000" :class="inputClass">
                </div>
            </div>
        </form>
        <template #footer>
            <Button @click="partenaire.open = false">Annuler</Button>
            <Button variant="primary" :disabled="partenaire.processing" @click="savePartenaire">Enregistrer</Button>
        </template>
    </Modal>
</template>
