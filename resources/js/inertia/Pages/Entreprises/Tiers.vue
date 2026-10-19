<script setup>
/**
 * Tiers personne morale du gestionnaire : rattacher une fiche existante du portefeuille
 * ou créer un nouveau prospect lié (même action POST, champ tier_mode).
 */
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SchemaForm from '@/Components/forms/SchemaForm.vue';

const props = defineProps({
    entreprise: { type: Object, required: true },
    saveUrl: { type: String, required: true },
    searchUrl: { type: String, required: true },
    liens: { type: Array, default: () => [] },
    prospectSchema: { type: Object, required: true },
});

const mode = ref('existing');
const relation = ref({ lien: '', commentaire: '' });
const query = ref('');
const results = ref([]);
const searching = ref(false);
const selected = ref(null);
let timer = null;

const existing = useForm({ selected_company_id: '' });

const search = () => {
    clearTimeout(timer);
    const q = query.value.trim();
    if (q.length < 2) {
        results.value = [];
        return;
    }
    timer = setTimeout(async () => {
        searching.value = true;
        try {
            const url = `${props.searchUrl}?${new URLSearchParams({ q, exclude_id: props.entreprise.id })}`;
            const response = await fetch(url, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            results.value = response.ok ? (await response.json()).data ?? [] : [];
        } finally {
            searching.value = false;
        }
    }, 300);
};

const choose = (item) => {
    selected.value = item;
    existing.selected_company_id = item.id;
};

const submitExisting = () => {
    existing.transform((data) => ({
        ...data,
        entreprise_id: props.entreprise.id,
        token: props.entreprise.token,
        tier_mode: 'existing',
        lien: relation.value.lien,
        commentaire: relation.value.commentaire,
    })).post(props.saveUrl, { preserveScroll: true });
};

const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head :title="`Tiers personne morale — ${entreprise.name}`" />
    <PageHeader title="Nouveau tiers personne morale" :subtitle="entreprise.name">
        <template #actions>
            <Button :href="entreprise.href" icon="arrow_back">Retour à la fiche</Button>
        </template>
    </PageHeader>

    <Card title="Rattachement">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="space-y-2 md:col-span-2">
                <span class="block text-xs font-semibold text-on-surface">Comment souhaitez-vous rattacher ce tiers ?</span>
                <div class="flex flex-wrap gap-2">
                    <label v-for="m in [['existing', 'Fiche existante du portefeuille', 'search'], ['new', 'Nouveau prospect', 'add_business']]" :key="m[0]"
                        class="flex cursor-pointer items-center gap-2 rounded-lg border px-3 py-2 text-xs font-semibold"
                        :class="mode === m[0] ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'">
                        <input v-model="mode" type="radio" name="tier_mode" :value="m[0]" class="text-secondary focus:ring-secondary">
                        <Icon :name="m[2]" class="text-base" /> {{ m[1] }}
                    </label>
                </div>
            </div>
            <div class="space-y-1.5">
                <label for="tiers-lien" class="block text-xs font-semibold text-on-surface">Nature du lien <span class="text-error">*</span></label>
                <input id="tiers-lien" v-model="relation.lien" type="text" list="tiers-liens" placeholder="Ex. fournisseur stratégique, partenaire, client…" :class="inputClass">
                <datalist id="tiers-liens"><option v-for="l in liens" :key="l" :value="l" /></datalist>
                <p v-if="existing.errors.lien" class="text-xs text-error">{{ existing.errors.lien }}</p>
            </div>
            <div class="space-y-1.5">
                <label for="tiers-commentaire" class="block text-xs font-semibold text-on-surface">Commentaire</label>
                <textarea id="tiers-commentaire" v-model="relation.commentaire" rows="2" :class="inputClass" />
            </div>
        </div>
    </Card>

    <!-- Fiche existante -->
    <Card v-if="mode === 'existing'" title="Recherche dans le portefeuille global">
        <div class="space-y-4">
            <div class="relative">
                <Icon name="search" class="pointer-events-none absolute left-2.5 top-1/2 -translate-y-1/2 text-base text-outline" />
                <input v-model="query" type="search" placeholder="Nom, RCCM, NIU, dirigeant, e-mail ou téléphone (2 caractères minimum)" class="pl-8" :class="inputClass" @input="search">
            </div>
            <p v-if="searching" class="text-xs text-on-surface-variant">Recherche…</p>
            <ul v-else-if="results.length" class="divide-y divide-surface-container-highest rounded-lg border border-surface-container-highest text-xs">
                <li v-for="r in results" :key="r.id" class="flex flex-wrap items-center justify-between gap-3 px-3 py-2.5" :class="selected?.id === r.id ? 'bg-secondary/10' : ''">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 font-semibold text-on-surface">{{ r.name }} <StatusBadge :variant="r.prospect ? 'warning' : 'success'">{{ r.status_label }}</StatusBadge></div>
                        <div class="text-on-surface-variant">{{ [r.rccm, r.niu, r.manager, r.phone, r.email, r.agence].filter(Boolean).join(' • ') || '—' }}</div>
                    </div>
                    <Button :variant="selected?.id === r.id ? 'success' : 'secondary'" :icon="selected?.id === r.id ? 'check' : 'link'" @click="choose(r)">{{ selected?.id === r.id ? 'Sélectionnée' : 'Sélectionner' }}</Button>
                </li>
            </ul>
            <p v-else-if="query.trim().length >= 2" class="text-xs text-on-surface-variant">Aucune fiche trouvée.</p>
            <p v-if="existing.errors.selected_company_id" class="text-xs text-error">{{ existing.errors.selected_company_id }}</p>
            <div class="flex justify-end">
                <Button variant="primary" icon="link" :disabled="!selected || existing.processing" @click="submitExisting">Lier la fiche sélectionnée</Button>
            </div>
        </div>
    </Card>

    <!-- Nouveau prospect -->
    <template v-else>
        <div class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
            <Icon name="info" class="text-blue-700" />
            <span>Si une fiche portant le même RCCM, NIU, e-mail ou téléphone existe déjà, elle sera liée au lieu d'être dupliquée.</span>
        </div>
        <SchemaForm :schema="prospectSchema" :extra="relation" />
    </template>
</template>
