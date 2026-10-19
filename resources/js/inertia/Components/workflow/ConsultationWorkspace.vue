<script setup>
/**
 * Consultation de l'instruction (administrateur, responsable régional) : notation PME, grille de notation,
 * rapport d'analyse critique, lien vers la grille des engagements.
 */
import Card from '@/Components/ui/Card.vue';
import Icon from '@/Components/ui/Icon.vue';
import GrilleNotation from '@/Components/workflow/GrilleNotation.vue';
import SmeCard from '@/Components/workflow/SmeCard.vue';
import AnalyseZones from '@/Components/workflow/AnalyseZones.vue';

defineProps({
    workspace: { type: Object, required: true },
});
</script>

<template>
    <div class="space-y-5">
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-3">
            <div class="space-y-5">
                <SmeCard :sme="workspace.sme" />
                <Card title="Grille des engagements">
                    <p class="mb-2 text-xs text-on-surface-variant">La grille consolidée par produit, par banque / EMF et par partenaire financier est disponible sur une page dédiée.</p>
                    <a v-if="workspace.engagementGridUrl" :href="workspace.engagementGridUrl" class="inline-flex items-center gap-1.5 text-xs font-semibold text-secondary hover:underline">
                        <Icon name="table_view" class="text-base" /> Ouvrir la grille
                    </a>
                </Card>
            </div>
            <div class="xl:col-span-2">
                <GrilleNotation :grille="workspace.grille" />
            </div>
        </div>
        <Card :title="workspace.zonesTitle">
            <AnalyseZones :zones="workspace.zones" empty-label="—" />
        </Card>
    </div>
</template>
