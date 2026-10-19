<script setup>
/**
 * Structuration d'un client par le chef de filière : analyses, identification des besoins, puis soumission
 * au chef d'agence. Formulaire verrouillé (lecture seule) une fois soumis ou validé ; l'état est calculé côté serveur.
 */
import { computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import RichTextEditor from '@/Components/ui/RichTextEditor.vue';
import RejectionBanner from '@/Components/workflow/RejectionBanner.vue';

const props = defineProps({
    entreprise: Object,
    badges: { type: Array, default: () => [] },
    rejection: { type: Object, default: null },
    editable: Boolean,
    lock: { type: String, default: null }, // pending | validated | null
    values: Object,
    readonly: Object,
    canSubmit: Boolean,
    urls: Object,
});

const form = useForm({ ...props.values });
const errorList = computed(() => Object.values(form.errors));

const analyses = [
    ['analyse_strategique', 'Analyse stratégique', 'Vision, marché, avantages concurrentiels, risques majeurs.'],
    ['analyse_operationnelle', 'Analyse opérationnelle', 'Organisation, processus, ressources, chaîne de valeur.'],
    ['analyse_eligibilite', 'Éligibilité', 'Adéquation aux critères des programmes et freins éventuels.'],
];
const notes = [
    ['identification_besoins', 'Identification des besoins', 'Besoins prioritaires tels qu\'identifiés avec l\'entreprise.'],
    ['qualification_notes', 'Notes de structuration', 'Remarques complémentaires.'],
];
const besoins = [
    ['besoin_financement', 'Financement', 'payments'],
    ['besoin_accompagnement', 'Accompagnement', 'support_agent'],
    ['besoin_structuration', 'Structuration', 'account_tree'],
];

const save = () => form.post(props.urls.update, { preserveScroll: true });
const submitToCa = () => {
    if (!window.confirm('Soumettre cette structuration au chef d\'agence pour validation ?')) return;
    router.post(props.urls.submit, {}, { preserveScroll: true });
};

const steps = ['Analyses', 'Besoins', 'Soumission chef d\'agence'];
</script>

<template>
    <Head :title="`Structuration — ${entreprise.name}`" />

    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-6 shadow-xs">
        <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-start">
            <div>
                <h1 class="text-xl font-bold tracking-tight text-on-surface">Structuration — {{ entreprise.name }}</h1>
                <p class="mt-1 max-w-2xl text-xs text-on-surface-variant">
                    Une seule structuration par client : analyses et besoins, puis soumission au chef d'agence.
                    Le dossier d'instruction (programmes et budgets d'appui) est composé sur la fiche client après validation du chef d'agence.
                </p>
            </div>
            <Button :href="urls.client" icon="arrow_back">Retour au dossier client</Button>
        </div>
        <div v-if="badges.length" class="mt-4 flex flex-wrap items-center gap-2 border-t border-surface-container-highest pt-4">
            <span v-for="b in badges" :key="b.label" class="inline-flex items-center gap-1.5">
                <StatusBadge :variant="b.variant">{{ b.label }}</StatusBadge>
                <span v-if="b.sub" class="text-[11px] text-on-surface-variant">{{ b.sub }}</span>
            </span>
        </div>
    </section>

    <ol class="grid grid-cols-3 gap-2 text-xs" aria-label="Étapes">
        <li v-for="(s, i) in steps" :key="s" class="flex items-center gap-2 rounded-lg border border-surface-container-highest bg-surface-container-lowest px-3 py-2 font-semibold text-on-surface">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-secondary text-[11px] text-white">{{ i + 1 }}</span>{{ s }}
        </li>
    </ol>

    <div v-if="rejection" class="space-y-1">
        <RejectionBanner :rejection="rejection" />
        <p class="text-xs text-on-surface-variant">Corrigez le contenu puis enregistrez et soumettez à nouveau.</p>
    </div>

    <!-- Lecture seule : structuration transmise ou validée -->
    <template v-if="!editable">
        <div v-if="lock === 'pending'" class="flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
            <Icon name="lock" class="text-amber-700" />
            <span><strong>En attente du chef d'agence.</strong> La structuration a été transmise ; vous ne pouvez plus la modifier tant que le chef d'agence n'a pas statué.</span>
        </div>
        <div v-else-if="lock === 'validated'" class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-900">
            <Icon name="verified" class="text-emerald-700" />
            <span><strong>Structuration validée.</strong> Composez le dossier d'instruction depuis la <a :href="urls.client" class="font-semibold underline">fiche client</a>.</span>
        </div>

        <Card title="Besoins identifiés">
            <div v-if="readonly.besoins.length" class="flex flex-wrap gap-1.5 text-xs">
                <span v-for="b in readonly.besoins" :key="b" class="rounded border border-surface-container-highest bg-surface-container-low px-2 py-0.5 font-semibold">{{ b }}</span>
            </div>
            <p v-else class="text-xs text-on-surface-variant">Aucun besoin coché.</p>
        </Card>
        <Card v-for="s in readonly.sections" :key="s.label" :title="s.label">
            <SafeHtml :html="s.html" class="text-xs leading-relaxed text-on-surface" />
        </Card>
        <Card v-if="readonly.programmes.length" title="Programmes retenus (ancien flux)">
            <ul class="divide-y divide-surface-container-highest text-xs">
                <li v-for="p in readonly.programmes" :key="p.name" class="flex items-center justify-between gap-2 py-2">
                    <span class="font-semibold text-on-surface">{{ p.name }}</span>
                    <span class="text-on-surface-variant">{{ p.type }} • {{ p.statut }}</span>
                </li>
            </ul>
        </Card>
    </template>

    <!-- Formulaire -->
    <form v-else class="space-y-5" @submit.prevent="save">
        <div v-if="errorList.length" class="rounded-lg border border-error/30 bg-error-container p-3 text-xs text-on-error-container">
            <p v-for="(message, i) in errorList" :key="i">{{ message }}</p>
        </div>

        <Card title="1. Analyses">
            <p class="-mt-2 mb-4 text-xs text-on-surface-variant">Positionnement stratégique, fonctionnement opérationnel et analyse d'éligibilité aux dispositifs.</p>
            <div class="space-y-5">
                <div v-for="[name, label, hint] in analyses" :key="name" class="space-y-1.5">
                    <label class="block text-xs font-semibold text-on-surface">{{ label }}</label>
                    <p class="text-[11px] text-on-surface-variant">{{ hint }}</p>
                    <RichTextEditor v-model="form[name]" min-height="8rem" />
                    <p v-if="form.errors[name]" class="text-xs text-error">{{ form.errors[name] }}</p>
                </div>
            </div>
        </Card>

        <Card title="2. Identification et notes">
            <p class="-mt-2 mb-4 text-xs text-on-surface-variant">Synthèse des besoins exprimés et notes internes pour la suite du dossier.</p>
            <div class="space-y-5">
                <div v-for="[name, label, hint] in notes" :key="name" class="space-y-1.5">
                    <label class="block text-xs font-semibold text-on-surface">{{ label }}</label>
                    <p class="text-[11px] text-on-surface-variant">{{ hint }}</p>
                    <RichTextEditor v-model="form[name]" min-height="8rem" />
                    <p v-if="form.errors[name]" class="text-xs text-error">{{ form.errors[name] }}</p>
                </div>
            </div>
        </Card>

        <Card title="3. Besoins identifiés">
            <p class="-mt-2 mb-4 text-xs text-on-surface-variant">Cochez les familles de besoins correspondant au profil de l'entreprise.</p>
            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                <label
                    v-for="[name, label, icon] in besoins" :key="name"
                    class="flex cursor-pointer items-center gap-3 rounded-lg border px-4 py-3 text-sm font-semibold"
                    :class="form[name] ? 'border-secondary bg-secondary/10 text-secondary' : 'border-surface-container-highest text-on-surface'"
                >
                    <input v-model="form[name]" type="checkbox" class="rounded text-secondary focus:ring-secondary">
                    <Icon :name="icon" class="text-lg" />
                    {{ label }}
                </label>
            </div>
            <div class="mt-5 flex justify-end">
                <Button type="submit" variant="primary" icon="check" :disabled="form.processing">Enregistrer la structuration</Button>
            </div>
        </Card>

        <Card v-if="canSubmit" title="4. Transmission au chef d'agence">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-on-surface-variant">Soumettez la structuration pour validation. Les programmes seront choisis ensuite sur la fiche client.</p>
                <Button variant="success" icon="send" @click="submitToCa">Soumettre au chef d'agence pour validation</Button>
            </div>
        </Card>
    </form>
</template>
