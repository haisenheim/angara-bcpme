<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Card from '@/Components/ui/Card.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import Button from '@/Components/ui/Button.vue';
import ActionDialog from '@/Components/workflow/ActionDialog.vue';

defineProps({
    entreprise: Object,
    // Lignes : { id, label, description, fourni, providedAt, url, notes?, uploadUrl? } ;
    // uploadUrl (optionnel) : dépôt / remplacement autorisé pour le profil courant.
    checklist: Array,
    title: { type: String, default: 'Pièces exigibles' },
    subtitle: { type: String, default: null },
    backLabel: { type: String, default: 'Retour à la fiche' },
});

const opened = ref(null);
const uploadAction = (c) => ({
    key: `piece-${c.id}`,
    title: `${c.fourni ? 'Remplacer' : 'Déposer'} — ${c.label}`,
    description: 'Formats acceptés : PDF, JPG ou PNG (10 Mo maximum).',
    fields: [
        { name: 'fichier', label: 'Fichier', type: 'file', required: true, accept: '.pdf,.jpg,.jpeg,.png' },
        { name: 'notes', label: 'Notes', type: 'textarea', value: c.notes ?? '' },
    ],
    submits: [{ label: 'Envoyer', variant: 'primary', url: c.uploadUrl }],
});
</script>

<template>
    <Head :title="`${title} — ${entreprise.name}`" />
    <PageHeader :title="title" :subtitle="subtitle ?? entreprise.name">
        <template #actions>
            <Button :href="entreprise.href" icon="arrow_back">{{ backLabel }}</Button>
        </template>
    </PageHeader>
    <ActionDialog v-if="opened" :key="opened.key" :action="opened" @close="opened = null" />
    <Card :title="`${checklist.filter((c) => c.fourni).length} / ${checklist.length} pièces fournies`">
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
                    <p v-if="c.notes" class="mt-1 text-on-surface">{{ c.notes }}</p>
                    <div class="mt-1 flex flex-wrap items-center gap-3">
                        <a v-if="c.url" :href="c.url" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Consulter <Icon name="open_in_new" class="text-sm" /></a>
                        <button v-if="c.uploadUrl" type="button" class="inline-flex items-center gap-1 font-semibold text-on-surface hover:text-secondary" @click="opened = uploadAction(c)">
                            <Icon :name="c.fourni ? 'sync' : 'upload_file'" class="text-sm" /> {{ c.fourni ? 'Remplacer' : 'Déposer' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-xs text-on-surface-variant">Aucune pièce exigible paramétrée.</p>
    </Card>
</template>
