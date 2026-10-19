<script setup>
/**
 * Dossier d'analyse critique d'une entreprise : synthèse (modifiable si updateUrl) et avis consolidés.
 */
import { Head, useForm } from '@inertiajs/vue3';
import PageHeader from '@/Components/ui/PageHeader.vue';
import Button from '@/Components/ui/Button.vue';
import Card from '@/Components/ui/Card.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import SafeHtml from '@/Components/ui/SafeHtml.vue';

const props = defineProps({
    entreprise: { type: Object, required: true },
    backLabel: { type: String, default: 'Retour à la fiche' },
    analyseCritique: { type: Object, default: null },
    // null = lecture seule.
    updateUrl: { type: String, default: null },
});

const form = useForm({
    statut: props.analyseCritique?.statut ?? '',
    synthese: props.analyseCritique?.synthese ?? '',
});
const submit = () => form.post(props.updateUrl, { preserveScroll: true });
const inputClass = 'w-full rounded-lg border-surface-container-highest text-sm focus:border-secondary focus:ring-secondary';
</script>

<template>
    <Head :title="`Analyse critique — ${entreprise.name}`" />
    <PageHeader title="Dossier d'analyse critique" :subtitle="`${entreprise.name} — consolidation des avis sur le prospect / client, l'entrée en relation et les dossiers d'instruction.`">
        <template #actions>
            <Button :href="entreprise.href" icon="arrow_back">{{ backLabel }}</Button>
        </template>
    </PageHeader>

    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <Card title="Synthèse" class="lg:col-span-5">
            <form v-if="updateUrl" class="space-y-4" @submit.prevent="submit">
                <div class="space-y-1.5">
                    <label for="ac-statut" class="block text-xs font-semibold text-on-surface">Statut</label>
                    <input id="ac-statut" v-model="form.statut" type="text" :class="inputClass">
                    <p v-if="form.errors.statut" class="text-xs text-error">{{ form.errors.statut }}</p>
                </div>
                <div class="space-y-1.5">
                    <label for="ac-synthese" class="block text-xs font-semibold text-on-surface">Synthèse consolidée</label>
                    <textarea id="ac-synthese" v-model="form.synthese" rows="14" :class="inputClass" />
                    <p v-if="form.errors.synthese" class="text-xs text-error">{{ form.errors.synthese }}</p>
                </div>
                <Button type="submit" variant="primary" icon="save" :disabled="form.processing">Enregistrer la synthèse</Button>
            </form>
            <dl v-else class="space-y-4 text-xs">
                <div>
                    <dt class="font-semibold uppercase tracking-wider text-on-surface-variant">Statut</dt>
                    <dd class="mt-1 text-on-surface">{{ analyseCritique?.statut || '—' }}</dd>
                </div>
                <div>
                    <dt class="font-semibold uppercase tracking-wider text-on-surface-variant">Synthèse consolidée</dt>
                    <dd class="mt-1 rounded-lg border border-surface-container-highest bg-surface-container-low p-3 text-on-surface">
                        <SafeHtml v-if="analyseCritique?.synthese" :html="analyseCritique.synthese" multiline />
                        <template v-else>—</template>
                    </dd>
                </div>
            </dl>
        </Card>

        <Card title="Avis consolidés" class="lg:col-span-7">
            <ul class="space-y-3 text-xs">
                <li v-for="(a, i) in analyseCritique?.avis ?? []" :key="i" class="rounded-lg border border-surface-container-highest p-3.5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold text-on-surface">{{ a.source }}</div>
                            <div class="text-on-surface-variant">{{ a.type }}</div>
                        </div>
                        <div class="text-right">
                            <StatusBadge v-if="a.etat">{{ a.etat }}</StatusBadge>
                            <div class="mt-1 text-on-surface-variant">{{ a.at ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="mt-2 text-on-surface-variant">{{ a.by }}</div>
                    <SafeHtml v-if="a.contenu" :html="a.contenu" multiline class="mt-1 leading-relaxed text-on-surface" />
                </li>
                <li v-if="!(analyseCritique?.avis ?? []).length" class="py-4 text-center text-on-surface-variant">Aucun avis n'a encore été consolidé.</li>
            </ul>
        </Card>
    </div>
</template>
