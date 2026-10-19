<script setup>
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

const props = defineProps({
    entreprise: Object,
    kpis: Array,
    sections: Array,
    activites: Object,
    sites: Array,
    equipe: Array,
    tiers: Object,
    checklist: Array,
    questionnaire: Array,
    structuration: Object,
    dossiers: Array,
    avis: Array,
    links: { type: Array, default: () => [] },
    actions: { type: Array, default: () => [] },
    // Actions de workflow décrites par le serveur (même contrat que la fiche dossier).
    workflowActions: { type: Array, default: () => [] },
    // Actions de section (ActionDialog), par onglet : { sites: [...], equipe: [...], tiers: [...], questionnaire: [...] }.
    // Les éléments de `sites`, `equipe` et `questionnaire` peuvent aussi porter leurs propres `actions`.
    sectionActions: { type: Object, default: () => ({}) },
    notice: { type: String, default: null },
    initialTab: { type: String, default: 'synthese' },
});

const tabs = computed(() => [
    { key: 'synthese', label: 'Synthèse' },
    { key: 'structuration', label: 'Structuration' },
    { key: 'dossiers', label: 'Dossiers', count: props.dossiers.length || null },
    { key: 'sites', label: 'Sites & équipe', count: (props.sites.length + props.equipe.length) || null },
    { key: 'tiers', label: 'Tiers', count: (props.tiers.physiques.length + props.tiers.morales.length) || null },
    { key: 'pieces', label: 'Pièces exigibles', count: `${props.checklist.filter((c) => c.fourni).length}/${props.checklist.length}` },
    { key: 'questionnaire', label: 'Questionnaire', count: props.questionnaire.length || null },
    { key: 'avis', label: 'Avis' },
]);
const tab = ref(props.initialTab);
const opened = ref(null);
const critere = ref(0);

// Au-delà de 4 liens d'en-tête, les suivants passent dans un menu « Plus ».
const visibleLinks = computed(() => (props.links.length > 4 ? props.links.slice(0, 3) : props.links));
const moreLinks = computed(() => (props.links.length > 4 ? props.links.slice(3) : []));
const moreOpen = ref(false);
const section = (key) => props.sectionActions?.[key] ?? [];
const hasMemberActions = computed(() => props.equipe.some((m) => m.actions?.length));
</script>

<template>
    <Head :title="entreprise.name" />

    <!-- En-tête -->
    <section class="rounded-xl border border-surface-container-highest bg-surface-container-lowest p-6 shadow-xs">
        <div class="flex flex-col justify-between gap-4 border-b border-surface-container-highest pb-5 lg:flex-row lg:items-center">
            <div>
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-bold tracking-tight text-on-surface">{{ entreprise.name }}</h1>
                    <StatusBadge :variant="entreprise.prospect ? 'warning' : 'success'">{{ entreprise.prospect ? 'Prospect' : 'Client' }}</StatusBadge>
                    <StatusBadge :variant="entreprise.statut.variant">{{ entreprise.statut.badge }}</StatusBadge>
                    <template v-if="entreprise.prospect && 'submittedAt' in entreprise">
                        <StatusBadge v-if="entreprise.submittedAt" variant="info">Soumis le {{ entreprise.submittedAt }}</StatusBadge>
                        <StatusBadge v-else>Brouillon — non soumis</StatusBadge>
                    </template>
                    <StatusBadge v-if="entreprise.taille">{{ entreprise.taille }}</StatusBadge>
                    <StatusBadge v-if="entreprise.caractere" :variant="entreprise.caractere === 'Formel' ? 'success' : 'warning'">{{ entreprise.caractere }}</StatusBadge>
                </div>
                <p class="text-xs text-on-surface-variant">
                    {{ entreprise.forme ?? '—' }} • {{ entreprise.agence ?? '—' }}<template v-if="entreprise.gestionnaire"> • Gestionnaire : {{ entreprise.gestionnaire }}</template>
                </p>
            </div>
            <div class="flex shrink-0 flex-wrap items-center gap-2.5">
                <Button v-for="l in visibleLinks" :key="l.href" :href="l.href" :icon="l.icon">{{ l.label }}</Button>
                <div v-if="moreLinks.length" class="relative" @mouseleave="moreOpen = false">
                    <Button icon="more_horiz" icon-right="expand_more" @click="moreOpen = !moreOpen">Plus</Button>
                    <div v-if="moreOpen" class="absolute right-0 z-20 mt-1 w-60 rounded-lg border border-surface-container-highest bg-white py-1 shadow-lg">
                        <a v-for="l in moreLinks" :key="l.href" :href="l.href" class="flex items-center gap-2 px-3 py-2 text-xs text-on-surface hover:bg-surface-container-low">
                            <Icon :name="l.icon ?? 'chevron_right'" class="text-base text-on-surface-variant" /> {{ l.label }}
                        </a>
                    </div>
                </div>
                <Button v-for="a in actions" :key="a.href" :href="a.href" :icon="a.icon" :variant="a.variant ?? 'primary'">{{ a.label }}</Button>
                <Button v-for="a in workflowActions" :key="a.key" :icon="a.icon" :variant="a.variant" @click="opened = a">{{ a.label }}</Button>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-3.5 pt-5 xl:grid-cols-4">
            <div v-for="k in kpis" :key="k.label" class="rounded-lg border border-surface-container-highest bg-surface-container-low/60 p-3.5">
                <div class="mb-1 text-[11px] font-semibold uppercase tracking-wider text-on-surface-variant">{{ k.label }}</div>
                <div class="text-xs font-bold text-on-surface">{{ k.value }}</div>
                <div v-if="k.sub" class="mt-0.5 text-[11px] text-on-surface-variant">{{ k.sub }}</div>
            </div>
        </div>
    </section>

    <div v-if="notice" class="flex items-start gap-3 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
        <Icon name="lock" class="text-blue-700" />
        <span>{{ notice }}</span>
    </div>

    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />

    <!-- Onglets -->
    <nav class="flex items-center gap-1 overflow-x-auto border-b border-surface-container-highest text-xs font-medium">
        <button
            v-for="t in tabs" :key="t.key" type="button"
            class="whitespace-nowrap border-b-2 px-4 py-2.5 transition-colors"
            :class="tab === t.key ? 'rounded-t-md border-primary bg-surface-container-lowest font-bold text-on-surface' : 'border-transparent text-on-surface-variant hover:text-on-surface'"
            @click="tab = t.key"
        >
            {{ t.label }}
            <span v-if="t.count" class="ml-1.5 rounded-full bg-secondary/15 px-1.5 text-[10px] font-bold text-secondary">{{ t.count }}</span>
        </button>
    </nav>

    <!-- Synthèse -->
    <div v-if="tab === 'synthese'" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <Card v-for="section in sections" :key="section.title" :title="section.title">
            <dl class="grid grid-cols-1 gap-x-6 gap-y-2.5 text-xs sm:grid-cols-2">
                <div v-for="[label, value] in section.items" :key="label">
                    <dt class="text-on-surface-variant">{{ label }}</dt>
                    <dd class="font-semibold text-on-surface">{{ value ?? '—' }}</dd>
                </div>
            </dl>
        </Card>
        <Card title="Produits et besoins exprimés">
            <div class="space-y-4 text-xs">
                <div v-for="[label, list] in [['Produits secondaires', activites.secondaires], ['Appuis financiers souhaités', activites.appuisFinanciers], ['Appuis non financiers souhaités', activites.appuisNonFinanciers]]" :key="label">
                    <div class="mb-1.5 font-semibold text-on-surface">{{ label }}</div>
                    <div v-if="list.length" class="flex flex-wrap gap-1.5">
                        <span v-for="x in list" :key="x" class="rounded border border-surface-container-highest bg-surface-container-low px-2 py-0.5">{{ x }}</span>
                    </div>
                    <p v-else class="text-on-surface-variant">Non renseigné.</p>
                </div>
            </div>
        </Card>
    </div>

    <!-- Structuration -->
    <div v-else-if="tab === 'structuration'" class="space-y-5">
        <Card title="Statut">
            <div class="flex flex-wrap items-center gap-3 text-xs">
                <StatusBadge :variant="structuration.status.variant">{{ structuration.status.badge }}</StatusBadge>
                <span v-if="structuration.status.detail" class="text-on-surface-variant">{{ structuration.status.detail }}</span>
            </div>
            <div v-if="structuration.decision" class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                <StatusBadge :variant="structuration.decision.variant">{{ structuration.decision.label }}</StatusBadge>
                <span class="text-on-surface-variant">{{ structuration.decision.by }} • {{ structuration.decision.at }}</span>
            </div>
            <ol v-if="structuration.events?.length" class="mt-4 space-y-1.5 text-xs">
                <li v-for="e in structuration.events" :key="e.label" class="flex items-center gap-2">
                    <Icon name="check_circle" class="text-sm text-secondary" />
                    <span class="font-semibold text-on-surface">{{ e.label }}</span>
                    <span class="text-on-surface-variant">{{ e.by ? e.by + ' • ' : '' }}{{ e.at }}</span>
                </li>
            </ol>
        </Card>
        <Card v-if="structuration.programmes?.length" title="Programmes retenus">
            <div class="flex flex-wrap gap-1.5 text-xs">
                <span v-for="p in structuration.programmes" :key="p" class="rounded border border-surface-container-highest bg-brand-subtle px-2 py-0.5 font-semibold">{{ p }}</span>
            </div>
        </Card>
        <Card v-if="structuration.besoins?.length" title="Besoins identifiés">
            <div class="flex flex-wrap gap-1.5 text-xs">
                <span v-for="b in structuration.besoins" :key="b" class="rounded border border-surface-container-highest bg-surface-container-low px-2 py-0.5 font-semibold">{{ b }}</span>
            </div>
        </Card>
        <Card v-for="s in structuration.sections ?? []" :key="s.label" :title="s.label">
            <SafeHtml :html="s.html" class="text-xs leading-relaxed text-on-surface" />
        </Card>
        <Card v-if="structuration.analyseCritique" title="Analyse critique">
            <template v-if="structuration.analyseCritique.statut" #actions>
                <StatusBadge>{{ structuration.analyseCritique.statut }}</StatusBadge>
            </template>
            <p v-if="structuration.analyseCritique.synthese" class="mb-4 whitespace-pre-wrap text-xs leading-relaxed text-on-surface">{{ structuration.analyseCritique.synthese }}</p>
            <ul class="divide-y divide-surface-container-highest text-xs">
                <li v-for="(a, i) in structuration.analyseCritique.avis" :key="i" class="py-3">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <span class="font-semibold text-on-surface">{{ a.source }}</span>
                        <span class="flex items-center gap-2 text-on-surface-variant">
                            <StatusBadge v-if="a.etat">{{ a.etat }}</StatusBadge>{{ a.by }}<template v-if="a.at"> • {{ a.at }}</template>
                        </span>
                    </div>
                    <p v-if="a.contenu" class="mt-1 whitespace-pre-wrap text-on-surface">{{ a.contenu }}</p>
                </li>
                <li v-if="!structuration.analyseCritique.avis.length" class="py-3 text-on-surface-variant">Aucun avis n'a encore été consolidé.</li>
            </ul>
        </Card>
        <p v-if="!structuration.started" class="text-xs text-on-surface-variant">La structuration n'a pas encore été réalisée par le chef de filière.</p>
    </div>

    <!-- Dossiers -->
    <Card v-else-if="tab === 'dossiers'" title="Dossiers d'instruction">
        <ul class="divide-y divide-surface-container-highest text-xs">
            <li v-for="d in dossiers" :key="d.token" class="flex flex-wrap items-center justify-between gap-3 py-3">
                <div class="min-w-0 space-y-1">
                    <div v-for="p in d.programmes" :key="p.name" class="text-on-surface">
                        <span class="font-semibold">{{ p.name }}</span>
                        <span v-if="p.financier" class="text-on-surface-variant"> — financier {{ p.financier }} ; non financier {{ p.nonFinancier }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <StatusBadge :variant="d.statut.variant">{{ d.statut.badge }}</StatusBadge>
                        <span v-if="d.etape" class="text-on-surface-variant">{{ d.etape }}</span>
                        <span v-if="d.transmisLe" class="text-on-surface-variant">• transmis le {{ d.transmisLe }}</span>
                    </div>
                </div>
                <Link v-if="d.href" :href="d.href" class="inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Ouvrir <Icon name="chevron_right" class="text-base" /></Link>
            </li>
            <li v-if="!dossiers.length" class="py-6 text-center text-on-surface-variant">Aucun dossier lié pour le moment.</li>
        </ul>
    </Card>

    <!-- Sites & équipe -->
    <div v-else-if="tab === 'sites'" class="space-y-5">
        <Card title="Sites">
            <template v-if="section('sites').length" #actions>
                <Button v-for="a in section('sites')" :key="a.key" :icon="a.icon" :variant="a.variant ?? 'secondary'" @click="opened = a">{{ a.label }}</Button>
            </template>
            <div v-if="sites.length" class="grid grid-cols-1 gap-3 md:grid-cols-2">
                <div v-for="s in sites" :key="s.id" class="rounded-lg border border-surface-container-highest p-3.5 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <div class="font-bold text-on-surface">{{ s.libelle }}</div>
                        <div v-if="s.actions?.length" class="flex shrink-0 items-center gap-1">
                            <button v-for="a in s.actions" :key="a.key" type="button" class="rounded-lg p-1.5 hover:bg-surface-container-low" :class="a.variant === 'ghost-danger' ? 'text-error' : 'text-on-surface-variant hover:text-on-surface'" :title="a.label" @click="opened = a">
                                <Icon :name="a.icon" class="text-base" />
                            </button>
                        </div>
                    </div>
                    <div class="text-on-surface-variant">{{ s.localite }}<template v-if="s.villageQuartier"> — {{ s.villageQuartier }}</template></div>
                    <div v-if="s.gps" class="mt-1 text-on-surface-variant">GPS : {{ s.gps }}</div>
                    <div v-if="s.contact" class="text-on-surface-variant">{{ s.contact }}</div>
                    <SafeHtml v-if="s.divers" :html="s.divers" class="mt-1 text-on-surface" />
                </div>
            </div>
            <p v-else class="text-xs text-on-surface-variant">Aucun site renseigné.</p>
        </Card>
        <Card title="Équipe" :padded="false">
            <div v-if="section('equipe').length" class="flex justify-end gap-2 px-5 pt-5">
                <Button v-for="a in section('equipe')" :key="a.key" :icon="a.icon" :variant="a.variant ?? 'secondary'" @click="opened = a">{{ a.label }}</Button>
            </div>
            <div class="overflow-x-auto px-5 pb-5">
                <table class="w-full text-left text-xs">
                    <thead class="border-b border-surface-container-highest text-[11px] uppercase tracking-wider text-on-surface-variant">
                        <tr><th class="py-2">Nom</th><th class="py-2">Fonction</th><th class="py-2">Âge</th><th class="py-2">Niveau</th><th class="py-2">Associé</th><th class="py-2">Site</th><th class="py-2">Contact</th><th v-if="hasMemberActions" class="py-2"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-surface-container-highest">
                        <tr v-for="m in equipe" :key="m.id">
                            <td class="py-2.5 font-semibold text-on-surface">{{ m.nom }} <StatusBadge v-if="m.dirigeant" variant="info">Dirigeant</StatusBadge></td>
                            <td class="py-2.5">{{ m.fonction ?? '—' }}</td>
                            <td class="py-2.5">{{ m.age ?? '—' }}</td>
                            <td class="py-2.5">{{ m.niveau ?? '—' }}</td>
                            <td class="py-2.5">{{ m.associe ? 'Oui' : 'Non' }}</td>
                            <td class="py-2.5">{{ m.site ?? '—' }}</td>
                            <td class="py-2.5">{{ m.contact ?? '—' }}<a v-if="m.cni" :href="m.cni" target="_blank" rel="noopener" class="ml-1 font-semibold text-secondary hover:underline">CNI</a></td>
                            <td v-if="hasMemberActions" class="py-2.5 text-right whitespace-nowrap">
                                <button v-for="a in m.actions ?? []" :key="a.key" type="button" class="rounded-lg p-1.5 hover:bg-surface-container-low" :class="a.variant === 'ghost-danger' ? 'text-error' : 'text-on-surface-variant hover:text-on-surface'" :title="a.label" @click="opened = a">
                                    <Icon :name="a.icon" class="text-base" />
                                </button>
                            </td>
                        </tr>
                        <tr v-if="!equipe.length"><td :colspan="hasMemberActions ? 8 : 7" class="py-6 text-center text-on-surface-variant">Aucun membre renseigné.</td></tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </div>

    <!-- Tiers -->
    <div v-else-if="tab === 'tiers'" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <Card v-for="[title, list] in [['Tiers personnes physiques', tiers.physiques], ['Tiers personnes morales', tiers.morales]]" :key="title" :title="title">
            <ul class="divide-y divide-surface-container-highest text-xs">
                <li v-for="t in list" :key="t.name" class="py-2.5">
                    <div class="flex items-center justify-between gap-2">
                        <a v-if="t.href" :href="t.href" class="font-semibold text-on-surface hover:text-secondary">{{ t.name }}</a>
                        <span v-else class="font-semibold text-on-surface">{{ t.name }}</span>
                        <StatusBadge v-if="t.statut">{{ t.statut }}</StatusBadge>
                    </div>
                    <div class="text-on-surface-variant">{{ t.lien ?? '—' }}<template v-if="t.contact"> • {{ t.contact }}</template><template v-if="t.niu"> • NIU {{ t.niu }}</template></div>
                    <p v-if="t.commentaire" class="mt-1 text-on-surface">{{ t.commentaire }}</p>
                </li>
                <li v-if="!list.length" class="py-4 text-center text-on-surface-variant">Aucun tiers.</li>
            </ul>
        </Card>
    </div>

    <!-- Pièces exigibles -->
    <Card v-else-if="tab === 'pieces'" title="Checklist des pièces exigibles">
        <div v-if="checklist.length" class="grid grid-cols-1 gap-3 md:grid-cols-2">
            <div v-for="c in checklist" :key="c.id" class="flex items-start gap-3 rounded-lg border border-surface-container-highest p-3.5 text-xs">
                <Icon :name="c.fourni ? 'check_circle' : 'radio_button_unchecked'" :filled="c.fourni" :class="c.fourni ? 'text-secondary' : 'text-outline'" />
                <div class="min-w-0 flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-semibold text-on-surface">{{ c.label }}</span>
                        <StatusBadge :variant="c.fourni ? 'success' : 'neutral'">{{ c.fourni ? 'Fourni' : 'Non fourni' }}</StatusBadge>
                    </div>
                    <p v-if="c.description" class="mt-0.5 text-on-surface-variant">{{ c.description }}</p>
                    <p v-if="c.providedAt" class="mt-1 text-on-surface-variant">Fourni le {{ c.providedAt }}</p>
                    <a v-if="c.url" :href="c.url" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Consulter <Icon name="open_in_new" class="text-sm" /></a>
                </div>
            </div>
        </div>
        <p v-else class="text-xs text-on-surface-variant">Aucune pièce exigible paramétrée.</p>
    </Card>

    <!-- Questionnaire -->
    <div v-else-if="tab === 'questionnaire'">
        <div v-if="questionnaire.length" class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            <nav class="space-y-1 lg:col-span-3">
                <button
                    v-for="(q, i) in questionnaire" :key="q.critere" type="button"
                    class="flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-xs"
                    :class="critere === i ? 'bg-surface-container-lowest font-bold text-on-surface shadow-xs' : 'text-on-surface-variant hover:bg-surface-container-low'"
                    @click="critere = i"
                >
                    <span>{{ i + 1 }}. {{ q.critere }}</span>
                    <Icon v-if="q.avisGestionnaire" name="rate_review" class="text-sm text-secondary" />
                </button>
            </nav>
            <div class="space-y-5 lg:col-span-9">
                <Card v-for="g in questionnaire[critere].groups" :key="g.sousCritere" :title="g.sousCritere">
                    <dl class="space-y-2.5 text-xs">
                        <div v-for="r in g.reponses" :key="r.question">
                            <dt class="text-on-surface-variant">{{ r.question }}</dt>
                            <dd class="font-semibold text-on-surface">{{ r.choix }}</dd>
                        </div>
                    </dl>
                </Card>
                <Card v-if="questionnaire[critere].avisGestionnaire || questionnaire[critere].actions?.length" title="Avis du gestionnaire sur ce critère">
                    <template v-if="questionnaire[critere].actions?.length" #actions>
                        <Button v-for="a in questionnaire[critere].actions" :key="a.key" :icon="a.icon" :variant="a.variant ?? 'secondary'" @click="opened = a">{{ a.label }}</Button>
                    </template>
                    <template v-if="questionnaire[critere].avisGestionnaire">
                        <p class="mb-2 text-[11px] text-on-surface-variant">{{ questionnaire[critere].avisGestionnaire.by }} • {{ questionnaire[critere].avisGestionnaire.at }}</p>
                        <SafeHtml :html="questionnaire[critere].avisGestionnaire.html" class="text-xs leading-relaxed text-on-surface" />
                    </template>
                    <p v-else class="text-xs text-on-surface-variant">Aucun avis saisi pour ce critère.</p>
                </Card>
            </div>
        </div>
        <Card v-else><p class="text-xs text-on-surface-variant">Questionnaire non renseigné.</p></Card>
    </div>

    <!-- Avis juridique / conformité -->
    <div v-else class="grid grid-cols-1 gap-5 lg:grid-cols-2">
        <Card v-for="a in avis" :key="a.title" :title="a.title">
            <template #actions>
                <StatusBadge :variant="a.at ? 'success' : 'neutral'">{{ a.at ?? 'En attente' }}</StatusBadge>
            </template>
            <p v-if="a.by" class="mb-2 text-[11px] text-on-surface-variant">{{ a.by }}</p>
            <SafeHtml v-if="a.html" :html="a.html" class="text-xs leading-relaxed text-on-surface" />
            <p v-else class="text-xs text-on-surface-variant">Aucun avis enregistré pour l'instant.</p>
        </Card>
    </div>
</template>
