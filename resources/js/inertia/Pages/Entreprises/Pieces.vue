<script setup>
import { Head } from '@inertiajs/vue3';
import Icon from '@/Components/ui/Icon.vue';
import Card from '@/Components/ui/Card.vue';
import PageHeader from '@/Components/ui/PageHeader.vue';
import StatusBadge from '@/Components/ui/StatusBadge.vue';
import Button from '@/Components/ui/Button.vue';

defineProps({
    entreprise: Object,
    checklist: Array,
});
</script>

<template>
    <Head :title="`Pièces exigibles — ${entreprise.name}`" />
    <PageHeader title="Pièces exigibles" :subtitle="entreprise.name">
        <template #actions>
            <Button :href="entreprise.href" icon="arrow_back">Retour à la fiche</Button>
        </template>
    </PageHeader>
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
                    <a v-if="c.url" :href="c.url" target="_blank" rel="noopener" class="mt-1 inline-flex items-center gap-1 font-semibold text-secondary hover:underline">Consulter <Icon name="open_in_new" class="text-sm" /></a>
                </div>
            </div>
        </div>
        <p v-else class="text-xs text-on-surface-variant">Aucune pièce exigible paramétrée.</p>
    </Card>
</template>
